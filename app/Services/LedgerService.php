<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\FxRate;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class LedgerService
{
    public const BASE_CURRENCY = 'USD';

    /**
     * Record a payment transaction and post its double-entry journal entries.
     *
     * @param array<string, mixed> $data
     */
    public function recordTransaction(array $data): PaymentTransaction
    {
        return DB::transaction(function () use ($data): PaymentTransaction {
            $processedAt = now();
            $currency = strtoupper((string) $data['currency']);
            $rate = (float) $data['exchange_rate'];
            $gross = (float) $data['gross_amount'];
            $fee = (float) ($data['fee_amount'] ?? 0);
            $net = round($gross - $fee, 2);

            FxRate::firstOrCreate([
                'from_currency' => $currency,
                'to_currency' => self::BASE_CURRENCY,
                'rate' => $rate,
                'quoted_at' => $processedAt,
            ]);

            $invoice = null;
            if (in_array($data['type'], ['sale', 'renewal'], true)) {
                $customerEmail = $data['customer_email'] ?: null;
                $customer = $customerEmail
                    ? Customer::firstOrCreate(
                        ['email' => $customerEmail],
                        ['name' => $data['customer_name'] ?: 'Walk-in customer']
                    )
                    : Customer::create(['name' => $data['customer_name'] ?: 'Walk-in customer']);

                if ($customer->name !== ($data['customer_name'] ?: $customer->name)) {
                    $customer->update(['name' => $data['customer_name']]);
                }

                $invoice = Invoice::create([
                    'customer_id' => $customer->id,
                    'invoice_no' => 'INV-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                    'subtotal' => $gross,
                    'tax' => 0,
                    'total' => $gross,
                    'currency' => $currency,
                    'status' => 'paid',
                    'due_date' => now()->toDateString(),
                    'paid_at' => $processedAt,
                ]);
            }

            $transaction = PaymentTransaction::create([
                'invoice_id' => $invoice?->id,
                'provider' => $data['provider'],
                'provider_reference' => $data['provider_reference'] ?: 'TXN-'.Str::upper(Str::random(10)),
                'type' => $data['type'],
                'gross_amount' => $gross,
                'fee_amount' => $fee,
                'net_amount' => $net,
                'currency' => $currency,
                'base_currency' => self::BASE_CURRENCY,
                'exchange_rate' => $rate,
                'base_gross_amount' => round($gross * $rate, 2),
                'base_fee_amount' => round($fee * $rate, 2),
                'base_net_amount' => round($net * $rate, 2),
                'status' => 'posted',
                'processed_at' => $processedAt,
            ]);

            $this->postLedger($transaction);

            return $transaction;
        });
    }

    private function postLedger(PaymentTransaction $transaction): void
    {
        $description = Str::headline($transaction->type).' via '.$transaction->provider;

        if (in_array($transaction->type, ['sale', 'renewal'], true)) {
            $this->entry('1010', $transaction, $description.' net receivable', $transaction->net_amount, 0);
            $this->entry('6100', $transaction, $description.' processor fee', $transaction->fee_amount, 0);
            $this->entry(
                $transaction->type === 'renewal' ? '4020' : '4010',
                $transaction,
                $description.' revenue',
                0,
                $transaction->gross_amount
            );
            return;
        }

        $this->entry('6200', $transaction, $description.' outbound payout', $transaction->gross_amount, 0);
        $this->entry('6100', $transaction, $description.' payout fee', $transaction->fee_amount, 0);
        $this->entry(
            '1010',
            $transaction,
            $description.' clearing reduction',
            0,
            $transaction->gross_amount + $transaction->fee_amount
        );
    }

    private function entry(
        string $accountCode,
        PaymentTransaction $transaction,
        string $description,
        float $debit,
        float $credit
    ): void {
        $account = Account::where('code', $accountCode)->firstOrFail();

        $transaction->ledgerEntries()->create([
            'account_id' => $account->id,
            'description' => $description,
            'debit' => $debit,
            'credit' => $credit,
            'currency' => $transaction->currency,
            'exchange_rate' => $transaction->exchange_rate,
            'base_debit' => round($debit * (float) $transaction->exchange_rate, 2),
            'base_credit' => round($credit * (float) $transaction->exchange_rate, 2),
            'occurred_at' => $transaction->processed_at,
        ]);
    }
}
