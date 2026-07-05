@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Expenses</h2>
            <p>Capture operating costs, categories, payment methods, and uploaded receipt references.</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="{{ route('accounting.index') }}">Profit & Loss</a>
            <a class="link-button" href="{{ route('reports') }}">Monthly summaries</a>
        </div>
    </div>

    <section class="cards">
        <div class="card"><div class="label">Total Expenses</div><div class="value money">${{ number_format($summary['total'], 2) }}</div></div>
        <div class="card"><div class="label">This Month</div><div class="value money">${{ number_format($summary['monthly'], 2) }}</div></div>
        <div class="card"><div class="label">Receipts</div><div class="value">{{ $summary['receipts'] }}</div></div>
        <div class="card"><div class="label">Categories</div><div class="value">{{ $summary['categories'] }}</div></div>
    </section>

    <div class="grid two" style="margin-top:16px;">
        <section class="panel">
            <h2>Recent Expenses</h2>
            <div class="table-scroll">
                <table>
                    <thead><tr><th>Date</th><th>Description</th><th>Supplier</th><th>Method</th><th>Amount</th></tr></thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td>{{ $expense->spent_at?->format('M j, Y') }}</td>
                                <td><strong>{{ $expense->description }}</strong><div class="tiny">{{ $expense->category }}</div></td>
                                <td>{{ $expense->supplier?->name ?? 'Direct expense' }}</td>
                                <td>{{ str_replace('_', ' ', $expense->payment_method) }}</td>
                                <td class="money">{{ $expense->currency }} {{ number_format($expense->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5">No expenses captured yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $expenses->links() }}
        </section>

        <section class="panel">
            <h2>Categories</h2>
            <table>
                <thead><tr><th>Category</th><th>Total</th></tr></thead>
                <tbody>
                    @forelse ($byCategory as $category)
                        <tr><td>{{ $category->category }}</td><td class="money">${{ number_format($category->total, 2) }}</td></tr>
                    @empty
                        <tr><td colspan="2">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>
@endsection
