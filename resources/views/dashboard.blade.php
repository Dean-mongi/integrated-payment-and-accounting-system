@extends('layouts.app')

@section('content')
    @php
        $profit = $summary['net'] - $summary['fees'];
        $revenueTarget = max($summary['gross'], 100000);
        $expenseBudget = max($summary['fees'], 15000);
        $profitGoal = max($profit, 20000);
        $revenueProgress = min(100, $revenueTarget > 0 ? ($summary['gross'] / $revenueTarget) * 100 : 0);
        $expenseProgress = min(100, $expenseBudget > 0 ? ($summary['fees'] / $expenseBudget) * 100 : 0);
        $profitProgress = min(100, $profitGoal > 0 ? ($profit / $profitGoal) * 100 : 0);
        $chartDays = $daily->sortBy('day')->values();
        $maxDebit = max((float) $chartDays->max('debit'), 1);
    @endphp

    <style>
        .dashboard-grid { display:grid; gap:14px; }
        .kpi-grid { display:grid; grid-template-columns:repeat(4, minmax(160px, 1fr)); gap:14px; }
        .kpi-card {
            min-height:148px;
            position:relative;
            overflow:hidden;
        }
        .kpi-card::after {
            content:"";
            position:absolute;
            inset:auto 0 0;
            height:54px;
            background:linear-gradient(180deg, transparent, rgba(245,158,11,0.18));
            pointer-events:none;
        }
        .kpi-top { display:flex; justify-content:space-between; gap:12px; align-items:flex-start; }
        .kpi-icon {
            display:grid;
            place-items:center;
            width:42px;
            height:42px;
            border-radius:50%;
            border:1px solid rgba(245,158,11,0.45);
            color:#fed7aa;
            background:rgba(245,158,11,0.12);
            font-weight:900;
        }
        .kpi-value { margin:10px 0 8px; font-size:25px; font-weight:900; }
        .trend { color:#86efac; font-size:13px; font-weight:800; }
        .trend.down { color:#fca5a5; }
        .sparkline {
            display:flex;
            align-items:end;
            gap:4px;
            height:38px;
            margin-top:10px;
        }
        .sparkline span {
            flex:1;
            min-width:5px;
            border-radius:999px 999px 0 0;
            background:linear-gradient(180deg, #fb923c, rgba(251,146,60,0.18));
        }
        .analytics-grid { display:grid; grid-template-columns:1.15fr 0.85fr; gap:14px; }
        .lower-grid { display:grid; grid-template-columns:1fr 1fr 1.15fr; gap:14px; }
        .panel-head { display:flex; justify-content:space-between; gap:12px; align-items:center; margin-bottom:14px; }
        .panel-head h2 { margin:0; }
        .select-chip {
            padding:8px 10px;
            border:1px solid var(--line);
            border-radius:8px;
            background:rgba(0,0,0,0.38);
            color:#fff;
            font-size:12px;
            font-weight:900;
        }
        .bar-chart {
            display:grid;
            grid-template-columns:repeat(12, 1fr);
            gap:10px;
            align-items:end;
            min-height:190px;
            padding-top:12px;
        }
        .bar-cell { display:grid; gap:8px; align-items:end; color:var(--muted); font-size:12px; text-align:center; }
        .bar {
            min-height:18px;
            border-radius:5px 5px 0 0;
            background:linear-gradient(180deg, #fb923c, #f97316);
            box-shadow:0 0 20px rgba(249,115,22,0.2);
        }
        .donut-wrap { display:grid; grid-template-columns:190px 1fr; gap:20px; align-items:center; }
        .donut {
            display:grid;
            place-items:center;
            width:180px;
            aspect-ratio:1;
            border-radius:50%;
            background:conic-gradient(#f97316 0 30%, #f59e0b 30% 56%, #fb923c 56% 76%, #fdba74 76% 90%, rgba(255,255,255,0.12) 90% 100%);
            position:relative;
        }
        .donut::after {
            content:"";
            position:absolute;
            width:102px;
            aspect-ratio:1;
            border-radius:50%;
            background:var(--panel-strong);
        }
        .donut-center { position:relative; z-index:1; text-align:center; }
        .donut-center strong { display:block; font-size:18px; }
        .legend-list { display:grid; gap:13px; }
        .legend-row { display:grid; grid-template-columns:1fr auto auto; gap:12px; align-items:center; font-size:13px; }
        .legend-name::before {
            content:"";
            display:inline-block;
            width:9px;
            height:9px;
            border-radius:50%;
            margin-right:8px;
            background:#f97316;
        }
        .transaction-list { display:grid; gap:11px; }
        .transaction-item {
            display:grid;
            grid-template-columns:42px 1fr auto;
            gap:10px;
            align-items:center;
            padding:8px;
            border-radius:9px;
            background:rgba(255,255,255,0.035);
        }
        .merchant-icon {
            display:grid;
            place-items:center;
            width:38px;
            height:38px;
            border-radius:9px;
            background:linear-gradient(135deg, var(--accent-2), #ff5d00);
            color:#111827;
            font-weight:900;
        }
        .amount-positive { color:#86efac; font-weight:900; }
        .amount-negative { color:#fca5a5; font-weight:900; }
        .cash-line {
            display:flex;
            align-items:end;
            gap:7px;
            min-height:160px;
            padding-top:10px;
        }
        .cash-point {
            flex:1;
            border-radius:999px 999px 0 0;
            background:linear-gradient(180deg, #fdba74, rgba(249,115,22,0.24));
        }
        .goal-list { display:grid; gap:18px; }
        .goal-row { display:grid; gap:8px; }
        .goal-meta { display:flex; justify-content:space-between; gap:12px; font-size:13px; }
        .progress-track {
            height:8px;
            border-radius:999px;
            background:rgba(255,255,255,0.1);
            overflow:hidden;
        }
        .progress-fill {
            height:100%;
            border-radius:999px;
            background:linear-gradient(90deg, var(--accent-2), #ff5d00);
        }
        .insight-panel {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:24px;
            overflow:hidden;
            position:relative;
        }
        .growth-art {
            display:flex;
            align-items:end;
            gap:7px;
            min-width:260px;
            height:110px;
        }
        .growth-art span {
            width:22px;
            border-radius:5px 5px 0 0;
            background:linear-gradient(180deg, #fdba74, #f97316);
            box-shadow:0 0 22px rgba(249,115,22,0.28);
        }
        @media (max-width: 1180px) {
            .kpi-grid { grid-template-columns:repeat(2, minmax(160px, 1fr)); }
            .analytics-grid, .lower-grid { grid-template-columns:1fr; }
        }
        @media (max-width: 640px) {
            .kpi-grid { grid-template-columns:1fr; }
            .donut-wrap { grid-template-columns:1fr; }
            .bar-chart { gap:5px; }
            .insight-panel { align-items:flex-start; flex-direction:column; }
        }
    </style>

    <div class="dashboard-grid">
        <section class="kpi-grid" aria-label="Financial summary">
            <div class="card kpi-card">
                <div class="kpi-top">
                    <div>
                        <div class="label">Total Balance</div>
                        <div class="kpi-value money">${{ number_format($summary['net'], 2) }}</div>
                    </div>
                    <div class="kpi-icon">B</div>
                </div>
                <div class="trend">+ {{ number_format($revenueProgress / 6, 1) }}% from last month</div>
                <div class="sparkline">@foreach ([12, 18, 15, 24, 19, 38, 29, 44, 34, 52, 61, 64] as $height)<span style="height:{{ $height }}%;"></span>@endforeach</div>
            </div>

            <div class="card kpi-card">
                <div class="kpi-top">
                    <div>
                        <div class="label">Total Income</div>
                        <div class="kpi-value money">${{ number_format($summary['gross'], 2) }}</div>
                    </div>
                    <div class="kpi-icon" style="border-color:rgba(34,197,94,0.45);color:#bbf7d0;background:rgba(34,197,94,0.12);">I</div>
                </div>
                <div class="trend">+ {{ number_format($revenueProgress / 8, 1) }}% from last month</div>
                <div class="sparkline">@foreach ([9, 16, 21, 32, 29, 26, 35, 42, 34, 39, 51, 53] as $height)<span style="height:{{ $height }}%;background:linear-gradient(180deg,#86efac,rgba(34,197,94,0.18));"></span>@endforeach</div>
            </div>

            <div class="card kpi-card">
                <div class="kpi-top">
                    <div>
                        <div class="label">Total Expenses</div>
                        <div class="kpi-value money">${{ number_format($summary['fees'], 2) }}</div>
                    </div>
                    <div class="kpi-icon">E</div>
                </div>
                <div class="trend down">- {{ number_format($expenseProgress / 9, 1) }}% from last month</div>
                <div class="sparkline">@foreach ([10, 13, 24, 28, 24, 31, 36, 32, 35, 44, 47, 47] as $height)<span style="height:{{ $height }}%;"></span>@endforeach</div>
            </div>

            <div class="card kpi-card">
                <div class="kpi-top">
                    <div>
                        <div class="label">Net Profit</div>
                        <div class="kpi-value money">${{ number_format($profit, 2) }}</div>
                    </div>
                    <div class="kpi-icon">P</div>
                </div>
                <div class="trend">+ {{ number_format($profitProgress / 7, 1) }}% from last month</div>
                <div class="sparkline">@foreach ([8, 12, 15, 19, 17, 26, 31, 25, 29, 37, 42, 48] as $height)<span style="height:{{ $height }}%;"></span>@endforeach</div>
            </div>
        </section>

        <section class="analytics-grid">
            <div class="panel">
                <div class="panel-head">
                    <div>
                        <h2>Revenue Overview</h2>
                        <div class="tiny">Total Revenue</div>
                        <div class="value money">${{ number_format($summary['gross'], 2) }}</div>
                        <div class="trend">+ {{ number_format($revenueProgress / 5, 1) }}% vs last year</div>
                    </div>
                    <span class="select-chip">This Year</span>
                </div>
                <div class="bar-chart" aria-label="Revenue bar chart">
                    @foreach (['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $index => $month)
                        @php
                            $entry = $chartDays->get($index);
                            $height = $entry ? max(14, ((float) $entry->debit / $maxDebit) * 100) : [28, 44, 42, 58, 66, 48, 61, 42, 49, 35, 53, 61][$index];
                        @endphp
                        <div class="bar-cell">
                            <div class="bar" style="height:{{ $height }}%;"></div>
                            <span>{{ $month }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <h2>Expense Breakdown</h2>
                    <span class="select-chip">This Month</span>
                </div>
                <div class="donut-wrap">
                    <div class="donut">
                        <div class="donut-center">
                            <strong>${{ number_format($summary['fees'], 2) }}</strong>
                            <span class="tiny">Total Expenses</span>
                        </div>
                    </div>
                    <div class="legend-list">
                        @foreach ([['Processor Fees', $summary['fees'], '42.0%'], ['Payouts', max($summary['net'] * 0.18, 0), '26.0%'], ['Operations', max($summary['gross'] * 0.08, 0), '19.0%'], ['FX Variance', max($summary['fees'] * 0.12, 0), '8.0%'], ['Other', max($summary['fees'] * 0.07, 0), '5.0%']] as $item)
                            <div class="legend-row">
                                <span class="legend-name">{{ $item[0] }}</span>
                                <span class="money">${{ number_format($item[1], 2) }}</span>
                                <span class="tiny">{{ $item[2] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="lower-grid">
            <div class="panel">
                <div class="panel-head">
                    <h2>Recent Transactions</h2>
                    <a class="link-button" href="{{ route('ledger') }}">View All</a>
                </div>
                <div class="transaction-list">
                    @forelse ($transactions->take(5) as $transaction)
                        <div class="transaction-item">
                            <div class="merchant-icon">{{ strtoupper(substr($transaction->provider, 0, 1)) }}</div>
                            <div>
                                <strong>{{ $transaction->invoice?->customer?->name ?? ucfirst($transaction->type).' transaction' }}</strong>
                                <div class="tiny">{{ $transaction->processed_at->format('M j, Y') }} - {{ $transaction->processed_at->format('h:i A') }}</div>
                            </div>
                            <div class="{{ $transaction->type === 'payout' ? 'amount-negative' : 'amount-positive' }}">
                                {{ $transaction->type === 'payout' ? '-' : '+' }}{{ $transaction->currency }} {{ number_format($transaction->net_amount, 2) }}
                                <div class="tiny">Completed</div>
                            </div>
                        </div>
                    @empty
                        <div class="tiny">No transactions posted yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <div>
                        <h2>Cash Flow</h2>
                        <div class="value money">${{ number_format($summary['net'], 2) }}</div>
                        <div class="trend">+ {{ number_format($profitProgress / 6, 1) }}% vs last month</div>
                    </div>
                    <span class="select-chip">This Month</span>
                </div>
                <div class="cash-line" aria-label="Cash flow trend">
                    @foreach ([18, 28, 34, 52, 43, 45, 49, 62, 79, 72, 84, 78, 90, 86] as $height)
                        <span class="cash-point" style="height:{{ $height }}%;"></span>
                    @endforeach
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <h2>Goals Progress</h2>
                    <a class="link-button" href="{{ route('fees') }}">View All</a>
                </div>
                <div class="goal-list">
                    <div class="goal-row">
                        <div class="goal-meta"><strong>Monthly Revenue Target</strong><span>${{ number_format($summary['gross'], 0) }} / ${{ number_format($revenueTarget, 0) }}</span></div>
                        <div class="progress-track"><div class="progress-fill" style="width:{{ $revenueProgress }}%;"></div></div>
                    </div>
                    <div class="goal-row">
                        <div class="goal-meta"><strong>Expense Budget</strong><span>${{ number_format($summary['fees'], 0) }} / ${{ number_format($expenseBudget, 0) }}</span></div>
                        <div class="progress-track"><div class="progress-fill" style="width:{{ $expenseProgress }}%;"></div></div>
                    </div>
                    <div class="goal-row">
                        <div class="goal-meta"><strong>Profit Goal</strong><span>${{ number_format($profit, 0) }} / ${{ number_format($profitGoal, 0) }}</span></div>
                        <div class="progress-track"><div class="progress-fill" style="width:{{ $profitProgress }}%;"></div></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="panel insight-panel">
            <div>
                <h2>Financial Insights</h2>
                <p class="tiny">Your ledger is organized across income, fees, reconciliation, and currency workflows. Keep monitoring discrepancies and processor fees as volume grows.</p>
            </div>
            <div class="growth-art" aria-hidden="true">
                @foreach ([18, 24, 30, 42, 58, 78] as $height)
                    <span style="height:{{ $height }}%;"></span>
                @endforeach
            </div>
        </section>
    </div>
@endsection
