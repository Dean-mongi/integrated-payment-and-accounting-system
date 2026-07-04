<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\LedgerEntry;
use App\Models\PaymentTransaction;
use App\Models\Reconciliation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->adminUser())->get('/');

        $response->assertStatus(200);
    }

    public function test_process_pages_return_successful_responses(): void
    {
        $this->seedAccounts();
        $this->actingAs($this->adminUser());

        foreach (['ledger', 'reconciliation', 'fees', 'currency', 'analytics', 'reports', 'settings', 'invoices.index', 'invoices.create'] as $route) {
            $this->get(route($route))->assertStatus(200);
        }
    }

    public function test_sale_transaction_posts_balanced_ledger_entries(): void
    {
        $this->seedAccounts();
        $this->actingAs($this->adminUser());

        $response = $this->post(route('transactions.store'), [
            'type' => 'sale',
            'provider' => 'Stripe',
            'provider_reference' => 'TXN-1001',
            'gross_amount' => '100.00',
            'fee_amount' => '3.20',
            'currency' => 'USD',
            'exchange_rate' => '1',
            'customer_name' => 'Acme Stores',
            'customer_email' => 'billing@acme.test',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('payment_transactions', [
            'provider_reference' => 'TXN-1001',
            'net_amount' => 96.80,
            'base_net_amount' => 96.80,
        ]);

        $transaction = PaymentTransaction::firstOrFail();

        $this->assertSame(
            '0.00',
            number_format(
                LedgerEntry::where('payment_transaction_id', $transaction->id)->sum('base_debit')
                    - LedgerEntry::where('payment_transaction_id', $transaction->id)->sum('base_credit'),
                2,
                '.',
                ''
            )
        );
    }

    public function test_deposit_reconciliation_matches_posted_net_total(): void
    {
        $this->seedAccounts();
        $this->actingAs($this->adminUser());

        $this->post(route('transactions.store'), [
            'type' => 'sale',
            'provider' => 'Stripe',
            'provider_reference' => 'TXN-1002',
            'gross_amount' => '100.00',
            'fee_amount' => '3.20',
            'currency' => 'USD',
            'exchange_rate' => '1',
            'customer_name' => 'Acme Stores',
            'customer_email' => 'billing@acme.test',
        ]);

        $response = $this->post(route('reconciliations.store'), [
            'processor' => 'Stripe',
            'deposit_reference' => 'DEP-1002',
            'amount' => '96.80',
            'currency' => 'USD',
            'exchange_rate' => '1',
            'deposited_at' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('bank_deposits', [
            'deposit_reference' => 'DEP-1002',
            'base_amount' => 96.80,
        ]);

        $this->assertSame('matched', Reconciliation::firstOrFail()->status);
    }

    public function test_invoice_payment_generates_receipt_and_ledger_entries(): void
    {
        $this->seedAccounts();
        $this->actingAs($this->adminUser());

        $invoiceResponse = $this->post(route('invoices.store'), [
            'customer_name' => 'Acme Stores',
            'customer_email' => 'customer@example.com',
            'currency' => 'USD',
            'due_date' => now()->addDays(7)->toDateString(),
            'description' => ['Consulting service'],
            'quantity' => ['1'],
            'unit_price' => ['100.00'],
        ]);

        $invoiceResponse->assertRedirect();
        $this->assertDatabaseHas('invoices', ['customer_id' => 1, 'status' => 'unpaid', 'total' => 100.00]);

        $invoice = \App\Models\Invoice::firstOrFail();

        $paymentResponse = $this->post(route('invoice-payments.store', $invoice), [
            'payment_method' => 'card',
            'provider' => 'Stripe',
            'provider_reference' => 'PAY-1001',
            'amount' => '100.00',
            'fee_amount' => '3.00',
            'currency' => 'USD',
            'exchange_rate' => '1',
        ]);

        $paymentResponse->assertRedirect(route('invoices.show', $invoice));

        $this->assertDatabaseHas('invoices', ['id' => $invoice->id, 'status' => 'paid', 'paid_amount' => 100.00]);
        $this->assertDatabaseHas('receipts', ['invoice_id' => $invoice->id, 'amount' => 100.00]);
        $this->assertDatabaseHas('payment_transactions', ['provider_reference' => 'PAY-1001', 'fee_amount' => 3.00]);
    }

    private function adminUser(): User
    {
        return User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => 'password',
        ]);
    }

    private function seedAccounts(): void
    {
        collect([
            ['1010', 'Processor clearing', 'asset'],
            ['1020', 'Bank cash', 'asset'],
            ['4010', 'Sales revenue', 'revenue'],
            ['4020', 'Subscription revenue', 'revenue'],
            ['6100', 'Processor fees', 'expense'],
            ['6200', 'Outbound payouts', 'expense'],
            ['7100', 'Realized FX gain/loss', 'income_statement'],
        ])->each(fn (array $account) => Account::create([
            'code' => $account[0],
            'name' => $account[1],
            'type' => $account[2],
            'currency' => 'USD',
        ]));
    }
}
