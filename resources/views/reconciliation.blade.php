@extends('layouts.app')

@section('content')
    <style>
        .recon-grid { display:grid; grid-template-columns:minmax(280px, 420px) 1fr; gap:18px; align-items:start; }
        @media (max-width: 920px) { .recon-grid { grid-template-columns:1fr; } }
    </style>

    <div class="page-head">
        <div>
            <h2>Automatic Reconciliation</h2>
            <p>Daily deposits are matched against processor net totals and customer invoice activity, with differences flagged immediately for finance review.</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="{{ route('ledger') }}">Ledger tracking</a>
        </div>
    </div>

    <section class="cards" aria-label="Reconciliation summary">
        <div class="card">
            <div class="label">Matched deposits</div>
            <div class="value">{{ $summary['matched'] }}</div>
        </div>
        <div class="card">
            <div class="label">Discrepancies</div>
            <div class="value">{{ $summary['discrepancies'] }}</div>
        </div>
        <div class="card">
            <div class="label">Unreconciled deposits</div>
            <div class="value">{{ $summary['unreconciled_deposits'] }}</div>
        </div>
    </section>

    <div class="recon-grid" style="margin-top:18px;">
        <aside class="panel">
            <h2>Run deposit match</h2>
            @include('partials.reconciliation-form', ['prefix' => 'reconciliation'])
        </aside>

        <section class="stack">
            <div class="panel">
                <h2>Reconciliation reports</h2>
                <table>
                    <thead><tr><th>Deposit</th><th>Processor</th><th>Expected</th><th>Difference</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($reconciliations as $reconciliation)
                            <tr>
                                <td>{{ $reconciliation->bankDeposit?->deposit_reference ?? 'Daily report' }}<div class="tiny">{{ $reconciliation->created_at->format('M j, Y H:i') }}</div></td>
                                <td>{{ $reconciliation->bankDeposit?->processor ?? 'Multiple' }}</td>
                                <td class="money">{{ number_format($reconciliation->expected_net_amount, 2) }}</td>
                                <td class="money">{{ number_format($reconciliation->difference, 2) }}</td>
                                <td><span class="pill {{ $reconciliation->status === 'discrepancy' ? 'bad' : '' }}">{{ $reconciliation->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5">No reconciliation reports yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $reconciliations->links() }}
            </div>

            <div class="panel">
                <h2>Recent bank deposits</h2>
                <table>
                    <thead><tr><th>Reference</th><th>Processor</th><th>Amount</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($deposits as $deposit)
                            <tr>
                                <td>{{ $deposit->deposit_reference }}</td>
                                <td>{{ $deposit->processor }}</td>
                                <td class="money">{{ $deposit->currency }} {{ number_format($deposit->amount, 2) }}</td>
                                <td>{{ $deposit->deposited_at->format('M j, Y') }}</td>
                                <td><span class="pill {{ $deposit->reconciliation?->status === 'discrepancy' ? 'bad' : '' }}">{{ $deposit->reconciliation?->status ?? 'pending' }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5">No deposits recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
