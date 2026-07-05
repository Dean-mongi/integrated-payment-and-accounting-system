@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Accounting</h2>
            <p>Review general ledger totals, trial balance, profit and loss, balance sheet, and cash flow statement views.</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="{{ route('ledger') }}">General Ledger</a>
            <a class="link-button" href="{{ route('reports') }}">Reports</a>
        </div>
    </div>

    <section class="cards">
        <div class="card"><div class="label">Revenue</div><div class="value money">${{ number_format($statements['revenue'], 2) }}</div></div>
        <div class="card"><div class="label">Expenses</div><div class="value money">${{ number_format($statements['expenses'], 2) }}</div></div>
        <div class="card"><div class="label">Profit / Loss</div><div class="value money">${{ number_format($statements['profit'], 2) }}</div></div>
        <div class="card"><div class="label">Net Cash Flow</div><div class="value money">${{ number_format($statements['cash_in'] - $statements['cash_out'], 2) }}</div></div>
    </section>

    <div class="grid two" style="margin-top:16px;">
        <section class="panel">
            <h2>Trial Balance</h2>
            <div class="table-scroll">
                <table>
                    <thead><tr><th>Code</th><th>Account</th><th>Type</th><th>Balance</th></tr></thead>
                    <tbody>
                        @forelse ($balances as $account)
                            <tr>
                                <td>{{ $account->code }}</td>
                                <td>{{ $account->name }}</td>
                                <td>{{ $account->type }}</td>
                                <td class="money">${{ number_format($account->balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4">No ledger accounts available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="stack">
            <div class="panel">
                <h2>Profit & Loss</h2>
                <table>
                    <tbody>
                        <tr><td>Sales revenue</td><td class="money">${{ number_format($statements['revenue'], 2) }}</td></tr>
                        <tr><td>Payment fees and operating expenses</td><td class="money">${{ number_format($statements['expenses'], 2) }}</td></tr>
                        <tr><td><strong>Net profit</strong></td><td class="money"><strong>${{ number_format($statements['profit'], 2) }}</strong></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="panel">
                <h2>Balance Sheet</h2>
                <table>
                    <tbody>
                        <tr><td>Assets</td><td class="money">${{ number_format($statements['assets'], 2) }}</td></tr>
                        <tr><td>Liabilities</td><td class="money">${{ number_format($statements['liabilities'], 2) }}</td></tr>
                        <tr><td>Equity</td><td class="money">${{ number_format($statements['equity'], 2) }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="panel">
                <h2>Cash Flow Statement</h2>
                <table>
                    <tbody>
                        <tr><td>Cash in</td><td class="money">${{ number_format($statements['cash_in'], 2) }}</td></tr>
                        <tr><td>Cash out</td><td class="money">${{ number_format($statements['cash_out'], 2) }}</td></tr>
                        <tr><td><strong>Net cash flow</strong></td><td class="money"><strong>${{ number_format($statements['cash_in'] - $statements['cash_out'], 2) }}</strong></td></tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
