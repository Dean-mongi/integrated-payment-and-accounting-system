@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Split Fee Accounting</h2>
            <p>Gross revenue, processor fees, and net settlement are separated at transaction time so fee expense stays visible instead of being buried in deposits.</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="{{ route('ledger') }}">Post transaction</a>
            <a class="link-button" href="{{ route('reconciliation') }}">Reconcile deposits</a>
        </div>
    </div>

    <section class="cards" aria-label="Fee summary">
        <div class="card">
            <div class="label">Gross processed</div>
            <div class="value money">${{ number_format($summary['gross'], 2) }}</div>
        </div>
        <div class="card">
            <div class="label">Processor fees</div>
            <div class="value money">${{ number_format($summary['fees'], 2) }}</div>
        </div>
        <div class="card">
            <div class="label">Net settlement</div>
            <div class="value money">${{ number_format($summary['net'], 2) }}</div>
        </div>
    </section>

    <div class="grid two" style="margin-top:18px;">
        <section class="panel">
            <h2>Fees by processor</h2>
            <table>
                <thead><tr><th>Processor</th><th>Count</th><th>Gross</th><th>Fees</th><th>Net</th></tr></thead>
                <tbody>
                    @forelse ($providerFees as $provider)
                        <tr>
                            <td>{{ $provider->provider }}</td>
                            <td>{{ $provider->transaction_count }}</td>
                            <td class="money">{{ number_format($provider->gross_amount, 2) }}</td>
                            <td class="money">{{ number_format($provider->fee_amount, 2) }}</td>
                            <td class="money">{{ number_format($provider->net_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No processor fees posted yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="panel">
            <h2>Transaction fee audit</h2>
            <table>
                <thead><tr><th>Reference</th><th>Processor</th><th>Gross</th><th>Fee</th><th>Net</th></tr></thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->provider_reference }}<div class="tiny">{{ $transaction->type }}</div></td>
                            <td>{{ $transaction->provider }}</td>
                            <td class="money">{{ $transaction->currency }} {{ number_format($transaction->gross_amount, 2) }}</td>
                            <td class="money">{{ $transaction->currency }} {{ number_format($transaction->fee_amount, 2) }}</td>
                            <td class="money">{{ $transaction->currency }} {{ number_format($transaction->net_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No fee audit rows yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $transactions->links() }}
        </section>
    </div>
@endsection
