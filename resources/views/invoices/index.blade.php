@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Invoice Management</h2>
            <p>Create invoices, track paid/unpaid/partial/overdue status, receive payments, and download receipts.</p>
        </div>
        @if (in_array(auth()->user()->role, ['admin', 'cashier'], true))
            <a class="link-button" href="{{ route('invoices.create') }}">Create invoice</a>
        @endif
    </div>

    <section class="cards">
        <div class="card"><div class="label">Paid</div><div class="value">{{ $summary['paid'] }}</div></div>
        <div class="card"><div class="label">Unpaid</div><div class="value">{{ $summary['unpaid'] }}</div></div>
        <div class="card"><div class="label">Partially paid</div><div class="value">{{ $summary['partial'] }}</div></div>
        <div class="card"><div class="label">Overdue</div><div class="value">{{ $summary['overdue'] }}</div></div>
    </section>

    <section class="panel" style="margin-top:16px;">
        <h2>Invoices</h2>
        <table>
            <thead><tr><th>Invoice</th><th>Customer</th><th>Due</th><th>Status</th><th>Total</th><th>Paid</th></tr></thead>
            <tbody>
                @forelse ($invoices as $invoice)
                    <tr>
                        <td><a class="link-button" href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_no }}</a></td>
                        <td>{{ $invoice->customer?->name }}</td>
                        <td>{{ $invoice->due_date?->format('M j, Y') }}</td>
                        <td><span class="pill {{ in_array($invoice->status, ['overdue', 'unpaid'], true) ? 'bad' : '' }}">{{ str_replace('_', ' ', $invoice->status) }}</span></td>
                        <td class="money">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</td>
                        <td class="money">{{ $invoice->currency }} {{ number_format($invoice->paid_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">No invoices yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $invoices->links() }}
    </section>
@endsection
