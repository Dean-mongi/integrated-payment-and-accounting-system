@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>{{ $invoice->invoice_no }}</h2>
            <p>{{ $invoice->customer?->name }} - {{ $invoice->customer?->email }}</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="mailto:{{ $invoice->customer?->email }}?subject={{ rawurlencode('Invoice '.$invoice->invoice_no) }}">Send invoice</a>
            <a class="link-button" href="https://wa.me/?text={{ rawurlencode('Invoice '.$invoice->invoice_no.' total '.$invoice->currency.' '.number_format($invoice->total, 2)) }}">WhatsApp</a>
            <button type="button" onclick="window.print()">Download PDF</button>
            <a class="link-button" href="{{ route('invoices.index') }}">All invoices</a>
        </div>
    </div>

    <section class="cards">
        <div class="card"><div class="label">Status</div><div class="value" style="font-size:20px;">{{ str_replace('_', ' ', $invoice->status) }}</div></div>
        <div class="card"><div class="label">Total</div><div class="value money">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</div></div>
        <div class="card"><div class="label">Paid</div><div class="value money">{{ $invoice->currency }} {{ number_format($invoice->paid_amount, 2) }}</div></div>
        <div class="card"><div class="label">Due date</div><div class="value" style="font-size:20px;">{{ $invoice->due_date?->format('M j, Y') }}</div></div>
    </section>

    <div class="grid two" style="margin-top:16px;">
        <section class="panel">
            <h2>Invoice lines</h2>
            <table>
                <thead><tr><th>Description</th><th>Qty</th><th>Unit</th><th>Total</th></tr></thead>
                <tbody>
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ number_format($item->quantity, 2) }}</td>
                            <td class="money">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="money">{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <section class="panel">
            <h2>Receive payment</h2>
            <form method="post" action="{{ route('invoice-payments.store', $invoice) }}">
                @csrf
                <div class="split">
                    <div class="field"><label for="payment_method">Method</label><select id="payment_method" name="payment_method"><option value="mobile_money">Mobile money</option><option value="bank_transfer">Bank transfer</option><option value="cash">Cash</option><option value="card">Card</option><option value="paypal">PayPal</option><option value="stripe">Stripe</option></select></div>
                    <div class="field"><label for="provider">Provider</label><input id="provider" name="provider" value="Stripe"></div>
                </div>
                <div class="field"><label for="provider_reference">Provider reference</label><input id="provider_reference" name="provider_reference" placeholder="Optional auto-generated"></div>
                <div class="split">
                    <div class="field"><label for="amount">Amount</label><input id="amount" name="amount" type="number" step="0.01" value="{{ max($invoice->total - $invoice->paid_amount, 0) }}"></div>
                    <div class="field"><label for="fee_amount">Gateway fee</label><input id="fee_amount" name="fee_amount" type="number" step="0.01" value="0"></div>
                </div>
                <div class="split">
                    <div class="field"><label for="payment_currency">Currency</label><input id="payment_currency" name="currency" maxlength="3" value="{{ $invoice->currency }}"></div>
                    <div class="field"><label for="exchange_rate">Exchange rate to USD</label><input id="exchange_rate" name="exchange_rate" type="number" step="0.000001" value="1"></div>
                </div>
                <button type="submit">Confirm payment and generate receipt</button>
            </form>
        </section>
    </div>

    <section class="panel" style="margin-top:16px;">
        <h2>Receipts</h2>
        <table>
            <thead><tr><th>Receipt</th><th>Method</th><th>Amount</th><th>Issued</th></tr></thead>
            <tbody>
                @forelse ($invoice->receipts as $receipt)
                    <tr>
                        <td><a class="link-button" href="{{ route('receipts.show', $receipt) }}">{{ $receipt->receipt_no }}</a></td>
                        <td>{{ str_replace('_', ' ', $receipt->payment_method) }}</td>
                        <td class="money">{{ $receipt->currency }} {{ number_format($receipt->amount, 2) }}</td>
                        <td>{{ $receipt->issued_at->format('M j, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">No receipts generated yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
