@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Receipt {{ $receipt->receipt_no }}</h2>
            <p>Payment confirmation for invoice {{ $receipt->invoice->invoice_no }}.</p>
        </div>
        <a class="link-button" href="{{ route('invoices.show', $receipt->invoice) }}">Back to invoice</a>
    </div>

    <section class="panel">
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
