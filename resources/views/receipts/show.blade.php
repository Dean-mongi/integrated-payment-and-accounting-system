@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Receipt {{ $receipt->receipt_no }}</h2>
            <p>Payment confirmation for invoice {{ $receipt->invoice->invoice_no }}.</p>
        </div>
        <div class="toolbar">
            <button type="button" onclick="window.print()">Print</button>
            <a class="link-button" href="mailto:{{ $receipt->invoice->customer?->email }}?subject={{ rawurlencode('Receipt '.$receipt->receipt_no) }}">Email</a>
            <a class="link-button" href="https://wa.me/?text={{ rawurlencode('Receipt '.$receipt->receipt_no.' for '.$receipt->currency.' '.number_format($receipt->amount, 2)) }}">WhatsApp</a>
            <a class="link-button" href="{{ route('invoices.show', $receipt->invoice) }}">Back to invoice</a>
        </div>
    </div>

    <section class="panel">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
            <img src="{{ asset('images/malihub-logo.svg') }}" alt="MaliHub logo" style="width:82px;height:82px;object-fit:contain;background:#fff;border-radius:8px;">
            <div>
                <strong style="font-size:22px;">MaliHub</strong>
                <div class="tiny">Your Financial Hub. Grow Better.</div>
            </div>
        </div>
        <table>
            <tbody>
                <tr><td>Customer</td><td>{{ $receipt->invoice->customer?->name }}</td></tr>
                <tr><td>Payment method</td><td>{{ str_replace('_', ' ', $receipt->payment_method) }}</td></tr>
                <tr><td>Amount</td><td class="money">{{ $receipt->currency }} {{ number_format($receipt->amount, 2) }}</td></tr>
                <tr><td>Processor reference</td><td>{{ $receipt->paymentTransaction->provider_reference }}</td></tr>
                <tr><td>Issued</td><td>{{ $receipt->issued_at->format('M j, Y H:i') }}</td></tr>
            </tbody>
        </table>
    </section>
@endsection
