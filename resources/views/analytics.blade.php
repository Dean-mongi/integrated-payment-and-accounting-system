@extends('layouts.app')

@section('content')
    @php
        $maxMonthly = max((float) $monthly->max('gross'), 1);
    @endphp

    <style>
        .analytics-layout { display:grid; gap:16px; }
        .analytics-bars { display:grid; grid-template-columns:repeat(12, minmax(34px, 1fr)); gap:10px; min-height:220px; align-items:end; }
        .analytics-bar-item { display:grid; gap:8px; align-items:end; color:var(--muted); font-size:11px; text-align:center; }
        .analytics-bar { min-height:18px; border-radius:6px 6px 0 0; background:linear-gradient(180deg, var(--accent), #0f766e); }
        .analytics-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .metric-line { display:grid; grid-template-columns:1fr auto auto; gap:12px; align-items:center; padding:11px 0; border-bottom:1px solid var(--line); }
        .mix-pill { display:inline-flex; justify-content:center; min-width:58px; padding:5px 8px; border-radius:999px; background:rgba(34,197,94,0.12); color:#bbf7d0; font-weight:900; }
        @media (max-width: 900px) { .analytics-grid { grid-template-columns:1fr; } .analytics-bars { overflow:auto; } }
    </style>

    <div class="page-head">
        <div>
            <h2>Analytics</h2>
            <p>Track payment volume, processor performance, fee drag, and transaction mix from posted ledger data.</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="{{ route('reports') }}">Open reports</a>
            <a class="link-button" href="{{ route('settings') }}">System settings</a>
        </div>
    </div>

    <section class="cards" aria-label="Analytics summary">
        <div class="card"><div class="label">Transactions</div><div class="value">{{ $summary['transactions'] }}</div></div>
        <div class="card"><div class="label">Gross Volume</div><div class="value money">${{ number_format($summary['gross'], 2) }}</div></div>
        <div class="card"><div class="label">Processor Fees</div><div class="value money">${{ number_format($summary['fees'], 2) }}</div></div>
        <div class="card"><div class="label">Net Volume</div><div class="value money">${{ number_format($summary['net'], 2) }}</div></div>
    </section>

    <div class="analytics-layout" style="margin-top:16px;">
        <section class="panel">
            <h2>Monthly Payment Volume</h2>
            <div class="analytics-bars">
                @forelse ($monthly as $month)
                    <div class="analytics-bar-item">
                        <div class="analytics-bar" style="height:{{ max(14, ($month['gross'] / $maxMonthly) * 100) }}%;"></div>
                        <span>{{ explode(' ', $month['month'])[0] }}</span>
                    </div>
                @empty
                    <div class="tiny">No transaction analytics available yet.</div>
                @endforelse
            </div>
        </section>

        <div class="analytics-grid">
            <section class="panel">
                <h2>Processor Performance</h2>
                @forelse ($providerPerformance as $provider)
                    <div class="metric-line">
                        <div><strong>{{ $provider['provider'] }}</strong><div class="tiny">{{ $provider['count'] }} transactions</div></div>
                        <div class="money">${{ number_format($provider['net'], 2) }}</div>
                        <div class="tiny">{{ number_format($provider['fee_rate'], 2) }}% fee</div>
                    </div>
                @empty
                    <div class="tiny">No processor activity yet.</div>
                @endforelse
            </section>

            <section class="panel">
                <h2>Transaction Mix</h2>
                @forelse ($typeMix as $type)
                    <div class="metric-line">
                        <div><strong>{{ ucfirst($type['type']) }}</strong><div class="tiny">Net contribution</div></div>
                        <span class="mix-pill">{{ $type['count'] }}</span>
                        <div class="money">${{ number_format($type['net'], 2) }}</div>
                    </div>
                @empty
                    <div class="tiny">No sales, renewals, or payouts posted yet.</div>
                @endforelse
            </section>
        </div>
    </div>
@endsection
