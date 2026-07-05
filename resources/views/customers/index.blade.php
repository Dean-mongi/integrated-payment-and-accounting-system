@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Customers</h2>
            <p>Manage customer lists, profiles, invoice balances, and statement-ready activity.</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="{{ route('invoices.create') }}">Create invoice</a>
            <a class="link-button" href="{{ route('reports') }}">Customer statements</a>
        </div>
    </div>

    <section class="cards">
        <div class="card"><div class="label">Customers</div><div class="value">{{ $summary['customers'] }}</div></div>
        <div class="card"><div class="label">Invoiced</div><div class="value money">${{ number_format($summary['invoice_total'], 2) }}</div></div>
        <div class="card"><div class="label">Collected</div><div class="value money">${{ number_format($summary['paid_total'], 2) }}</div></div>
        <div class="card"><div class="label">Outstanding</div><div class="value money">${{ number_format($summary['outstanding'], 2) }}</div></div>
    </section>

    <section class="panel" style="margin-top:16px;">
        <h2>Customer List</h2>
        <div class="table-scroll">
            <table>
                <thead><tr><th>Customer</th><th>Country</th><th>Currency</th><th>Invoices</th><th>Total</th><th>Outstanding</th></tr></thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td><strong>{{ $customer->name }}</strong><div class="tiny">{{ $customer->email ?? 'No email saved' }}</div></td>
                            <td>{{ $customer->country ?? 'Not set' }}</td>
                            <td>{{ $customer->default_currency ?? 'USD' }}</td>
                            <td>{{ $customer->invoices_count }}</td>
                            <td class="money">${{ number_format($customer->invoice_total ?? 0, 2) }}</td>
                            <td class="money">${{ number_format(($customer->invoice_total ?? 0) - ($customer->paid_total ?? 0), 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No customers yet. Create an invoice to add one.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $customers->links() }}
    </section>
@endsection
