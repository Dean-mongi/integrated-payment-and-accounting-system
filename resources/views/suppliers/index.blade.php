@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Suppliers</h2>
            <p>Track vendor records, payables, purchase categories, and expense relationships.</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="{{ route('expenses.index') }}">Add expense</a>
            <a class="link-button" href="{{ route('reports') }}">Purchases report</a>
        </div>
    </div>

    <section class="cards">
        <div class="card"><div class="label">Suppliers</div><div class="value">{{ $summary['suppliers'] }}</div></div>
        <div class="card"><div class="label">Active</div><div class="value">{{ $summary['active'] }}</div></div>
        <div class="card"><div class="label">Supplier Balance</div><div class="value money">${{ number_format($summary['balance'], 2) }}</div></div>
        <div class="card"><div class="label">Expense Total</div><div class="value money">${{ number_format($summary['expense_total'], 2) }}</div></div>
    </section>

    <section class="panel" style="margin-top:16px;">
        <h2>Supplier Directory</h2>
        <div class="table-scroll">
            <table>
                <thead><tr><th>Supplier</th><th>Category</th><th>Phone</th><th>Status</th><th>Balance</th></tr></thead>
                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td><strong>{{ $supplier->name }}</strong><div class="tiny">{{ $supplier->email ?? 'No email saved' }}</div></td>
                            <td>{{ $supplier->category ?? 'General' }}</td>
                            <td>{{ $supplier->phone ?? 'Not set' }}</td>
                            <td><span class="pill {{ $supplier->status !== 'active' ? 'bad' : '' }}">{{ $supplier->status }}</span></td>
                            <td class="money">${{ number_format($supplier->balance, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No suppliers configured yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $suppliers->links() }}
    </section>
@endsection
