@extends('layouts.app')

@section('content')
    <style>
        .ledger-grid { display:grid; grid-template-columns:minmax(280px, 420px) 1fr; gap:18px; align-items:start; }
        @media (max-width: 920px) { .ledger-grid { grid-template-columns:1fr; } }
        .entry-list { max-height:520px; overflow:auto; }
    </style>

    <div class="page-head">
        <div>
            <h2>Unified Ledger Tracking</h2>
            <p>Inbound sales, subscription renewals, and outbound payouts post directly into double-entry ledger lines with processor, invoice, customer, and base-currency context.</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="{{ route('fees') }}">Fee accounting</a>
            <a class="link-button" href="{{ route('currency') }}">Currency handling</a>
        </div>
    </div>

    <div class="ledger-grid">
        <aside class="stack">
            <section class="panel">
                <h2>Post transaction</h2>
                @include('partials.transaction-form', ['prefix' => 'ledger'])
            </section>

            <section class="panel">
                <h2>Account balances</h2>
                <table>
                    <thead><tr><th>Code</th><th>Account</th><th>Balance</th></tr></thead>
                    <tbody>
                        @foreach ($balances as $account)
                            <tr>
                                <td>{{ $account->code }}</td>
                                <td>{{ $account->name }}<div class="tiny">{{ $account->type }}</div></td>
                                <td class="money">${{ number_format($account->balance, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </aside>

        <section class="stack">
            <div class="panel">
                <h2>Posted transactions</h2>
                <table class="responsive-table">
                    <thead><tr><th>Reference</th><th>Type</th><th>Customer</th><th>Gross</th><th>Net</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->provider_reference }}<div class="tiny">{{ $transaction->provider }} at {{ $transaction->processed_at->format('M j, Y H:i') }}</div></td>
                                <td><span class="pill">{{ $transaction->type }}</span></td>
                                <td>{{ $transaction->invoice?->customer?->name ?? 'Payout recipient' }}</td>
                                <td class="money">{{ $transaction->currency }} {{ number_format($transaction->gross_amount, 2) }}</td>
                                <td class="money">{{ $transaction->currency }} {{ number_format($transaction->net_amount, 2) }}</td>
                                <td><span class="pill">{{ $transaction->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6">No ledger transactions posted yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $transactions->links() }}
            </div>

            <div class="panel entry-list">
                <h2>Recent journal entries</h2>
                <table>
                    <thead><tr><th>Account</th><th>Description</th><th>Debit</th><th>Credit</th></tr></thead>
                    <tbody>
                        @forelse ($entries as $entry)
                            <tr>
                                <td>{{ $entry->account->code }}<div class="tiny">{{ $entry->account->name }}</div></td>
                                <td>{{ $entry->description }}<div class="tiny">{{ $entry->occurred_at->format('M j, Y H:i') }}</div></td>
                                <td class="money">{{ $entry->currency }} {{ number_format($entry->debit, 2) }}</td>
                                <td class="money">{{ $entry->currency }} {{ number_format($entry->credit, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4">No journal entries yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
