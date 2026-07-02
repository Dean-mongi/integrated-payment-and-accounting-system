@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Reports</h2>
            <p>Review ledger balances, transaction audit rows, and reconciliation outcomes from one reporting workspace.</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="{{ route('analytics') }}">Analytics</a>
            <a class="link-button" href="{{ route('reconciliation') }}">Reconcile</a>
        </div>
    </div>

    <section class="cards" aria-label="Report summary">
        <div class="card"><div class="label">Ledger Entries</div><div class="value">{{ $reportCards['ledger_entries'] }}</div></div>
        <div class="card"><div class="label">Transactions</div><div class="value">{{ $reportCards['transactions'] }}</div></div>
        <div class="card"><div class="label">Matched Reports</div><div class="value">{{ $reportCards['matched_reconciliations'] }}</div></div>
        <div class="card"><div class="label">Discrepancies</div><div class="value">{{ $reportCards['discrepancies'] }}</div></div>
    </section>

    <div class="grid two" style="margin-top:16px;">
        <section class="panel">
            <h2>Trial Balance</h2>
            <table>
                <thead><tr><th>Code</th><th>Account</th><th>Type</th><th>Balance</th></tr></thead>
                <tbody>
                    @foreach ($balances as $account)
                        <tr>
                            <td>{{ $account->code }}</td>
                            <td>{{ $account->name }}</td>
                            <td>{{ $account->type }}</td>
                            <td class="money">${{ number_format($account->balance, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <section class="panel">
            <h2>Reconciliation Report</h2>
            <table>
                <thead><tr><th>Reference</th><th>Status</th><th>Expected</th><th>Difference</th></tr></thead>
                <tbody>
                    @forelse ($reconciliations as $reconciliation)
                        <tr>
                            <td>{{ $reconciliation->bankDeposit?->deposit_reference ?? 'Daily report' }}</td>
                            <td><span class="pill {{ $reconciliation->status === 'discrepancy' ? 'bad' : '' }}">{{ $reconciliation->status }}</span></td>
                            <td class="money">${{ number_format($reconciliation->expected_net_amount, 2) }}</td>
                            <td class="money">${{ number_format($reconciliation->difference, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">No reconciliation reports yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>

    <section class="panel" style="margin-top:16px;">
        <h2>Transaction Audit Report</h2>
        <table class="responsive-table">
            <thead><tr><th>Reference</th><th>Type</th><th>Customer</th><th>Gross</th><th>Fees</th><th>Net</th></tr></thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->provider_reference }}<div class="tiny">{{ $transaction->processed_at->format('M j, Y H:i') }}</div></td>
                        <td>{{ $transaction->type }}</td>
                        <td>{{ $transaction->invoice?->customer?->name ?? 'Payout recipient' }}</td>
                        <td class="money">{{ $transaction->currency }} {{ number_format($transaction->gross_amount, 2) }}</td>
                        <td class="money">{{ $transaction->currency }} {{ number_format($transaction->fee_amount, 2) }}</td>
                        <td class="money">{{ $transaction->currency }} {{ number_format($transaction->net_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">No transaction report rows yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
