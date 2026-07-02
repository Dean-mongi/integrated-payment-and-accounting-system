@extends('layouts.app')

@section('content')
    <style>
        .metrics { display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-bottom:22px; }
        .metrics .metric { background:transparent; border:0; padding:0; box-shadow:none; }
        .metrics .metric .value { font-size:26px; }
        .grid { display:grid; grid-template-columns:minmax(280px, 420px) 1fr; gap:18px; align-items:start; }
        @media (max-width: 920px) { .grid { grid-template-columns:1fr; } }
        .stack { display:grid; gap:12px; }
        .row-card { padding:12px; }
        .panel h2 { margin: 0 0 14px; font-size:16px; font-weight:900; }
        table { font-size:14px; }
        @media (max-width: 560px) { th:nth-child(3), td:nth-child(3) { display:none; } }
    </style>

    <section class="metrics" aria-label="Financial summary">
        <div class="metric card">
            <div class="label">Gross volume</div>
            <div class="value money">${{ number_format($summary['gross'], 2) }}</div>
        </div>
        <div class="metric card">
            <div class="label">Processor fees</div>
            <div class="value money">${{ number_format($summary['fees'], 2) }}</div>
        </div>
        <div class="metric card">
            <div class="label">Net settlement</div>
            <div class="value money">${{ number_format($summary['net'], 2) }}</div>
        </div>
        <div class="metric card">
            <div class="label">Discrepancies</div>
            <div class="value">{{ $summary['discrepancies'] }}</div>
        </div>
    </section>

    <div class="grid">
        <aside>
            <section class="panel">
                <h2>Record transaction</h2>
                @include('partials.transaction-form', ['prefix' => 'dashboard_transaction'])
            </section>

            <section class="panel">
                <h2>Reconcile deposit</h2>
                @include('partials.reconciliation-form', ['prefix' => 'dashboard_recon'])
            </section>
        </aside>

        <section class="stack">
            <div class="panel">
                <h2>Recent transactions</h2>
                <table class="responsive-table">
                    <thead><tr><th>Reference</th><th>Type</th><th>Customer</th><th>Gross</th><th>Fee</th><th>Net</th></tr></thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->provider_reference }}<div class="tiny">{{ $transaction->provider }} at {{ $transaction->processed_at->format('M j, H:i') }}</div></td>
                                <td><span class="pill">{{ $transaction->type }}</span></td>
                                <td>{{ $transaction->invoice?->customer?->name ?? 'Payout recipient' }}</td>
                                <td class="money">{{ $transaction->currency }} {{ number_format($transaction->gross_amount, 2) }}</td>
                                <td class="money">{{ $transaction->currency }} {{ number_format($transaction->fee_amount, 2) }}</td>
                                <td class="money">{{ $transaction->currency }} {{ number_format($transaction->net_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6">No transactions posted yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="panel">
                <h2>Account balances</h2>
                <table>
                    <thead><tr><th>Code</th><th>Account</th><th>Type</th><th>Base balance</th></tr></thead>
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
            </div>

            <div class="panel">
                <h2>Reconciliation results</h2>
                <div class="stack">
                    @forelse ($reconciliations as $reconciliation)
                        <div class="row-card">
                            <strong>{{ $reconciliation->bankDeposit->deposit_reference }}</strong>
                            <span class="pill {{ $reconciliation->status === 'discrepancy' ? 'bad' : '' }}">{{ $reconciliation->status }}</span>
                            <div class="tiny">{{ $reconciliation->bankDeposit->processor }} {{ $reconciliation->bankDeposit->currency }} deposit on {{ $reconciliation->bankDeposit->deposited_at->format('M j, Y') }}</div>
                            <div class="money">Expected {{ number_format($reconciliation->expected_net_amount, 2) }} | Difference {{ number_format($reconciliation->difference, 2) }}</div>
                        </div>
                    @empty
                        <div class="tiny">No reconciliations yet.</div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
@endsection
