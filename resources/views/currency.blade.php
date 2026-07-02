@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Multi-Currency Handling</h2>
            <p>Each transaction locks the exchange rate at posting time, records base-currency gross, fee, and net values, and keeps an FX rate trail for review.</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="{{ route('ledger') }}">Post foreign transaction</a>
            <a class="link-button" href="{{ route('fees') }}">Fee accounting</a>
        </div>
    </div>

    <section class="cards" aria-label="Currency summary">
        <div class="card">
            <div class="label">Base currency</div>
            <div class="value">{{ $summary['base_currency'] }}</div>
        </div>
        <div class="card">
            <div class="label">Foreign gross in base</div>
            <div class="value money">${{ number_format($summary['foreign_volume'], 2) }}</div>
        </div>
        <div class="card">
            <div class="label">Foreign fees in base</div>
            <div class="value money">${{ number_format($summary['foreign_fees'], 2) }}</div>
        </div>
        <div class="card">
            <div class="label">Locked rate records</div>
            <div class="value">{{ $summary['rate_count'] }}</div>
        </div>
    </section>

    <div class="grid two" style="margin-top:18px;">
        <section class="panel">
            <h2>Locked exchange rates</h2>
            <table>
                <thead><tr><th>Pair</th><th>Rate</th><th>Quoted at</th></tr></thead>
                <tbody>
                    @forelse ($rates as $rate)
                        <tr>
                            <td>{{ $rate->from_currency }} / {{ $rate->to_currency }}</td>
                            <td class="money">{{ number_format($rate->rate, 6) }}</td>
                            <td>{{ $rate->quoted_at->format('M j, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No exchange rates locked yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="panel">
            <h2>Foreign currency transactions</h2>
            <table>
                <thead><tr><th>Reference</th><th>Currency</th><th>Gross</th><th>Rate</th><th>Base net</th></tr></thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->provider_reference }}<div class="tiny">{{ $transaction->provider }}</div></td>
                            <td>{{ $transaction->currency }}</td>
                            <td class="money">{{ number_format($transaction->gross_amount, 2) }}</td>
                            <td class="money">{{ number_format($transaction->exchange_rate, 6) }}</td>
                            <td class="money">{{ $transaction->base_currency }} {{ number_format($transaction->base_net_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No foreign currency transactions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $transactions->links() }}
        </section>
    </div>
@endsection
