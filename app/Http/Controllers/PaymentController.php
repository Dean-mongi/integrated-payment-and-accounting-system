<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Services\LedgerService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, Invoice $invoice, LedgerService $ledgerService)
    {
        $invoice->load('customer');

        abort_if(
            $request->user()->role === 'customer' && $invoice->customer?->email !== $request->user()->email,
            403
        );

        $data = $request->validate([
            'payment_method' => ['required', 'in:card,mobile_money,bank_transfer,cash,paypal,stripe'],
            'provider' => ['required', 'string', 'max:50'],
            'provider_reference' => ['nullable', 'string', 'max:100', 'unique:payment_transactions,provider_reference'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'fee_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'exchange_rate' => ['required', 'numeric', 'min:0.000001'],
        ]);

        $transaction = $ledgerService->recordInvoicePayment($invoice, $data);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'event' => 'invoice.payment_received',
            'auditable_type' => Invoice::class,
            'auditable_id' => $invoice->id,
            'metadata' => ['transaction_id' => $transaction->id, 'amount' => $data['amount']],
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('invoices.show', $invoice)->with('status', 'Payment confirmed and receipt generated.');
    }

    public function receipt(Request $request, Receipt $receipt)
    {
        $receipt->load('invoice.customer', 'paymentTransaction');

        abort_if(
            $request->user()->role === 'customer' && $receipt->invoice->customer?->email !== $request->user()->email,
            403
        );

        return view('receipts.show', compact('receipt'));
    }
}
