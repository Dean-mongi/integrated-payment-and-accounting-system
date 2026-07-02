@extends('layouts.app')

@section('content')
    <div class="page-head">
        <div>
            <h2>Settings</h2>
            <p>Check operational configuration, chart-of-accounts setup, active processors, and quick finance workflow controls.</p>
        </div>
        <div class="toolbar">
            <a class="link-button" href="{{ route('ledger') }}">Post transaction</a>
            <a class="link-button" href="{{ route('reconciliation') }}">Run reconciliation</a>
        </div>
    </div>

    <section class="cards" aria-label="System settings">
        <div class="card"><div class="label">Application</div><div class="value" style="font-size:18px;">{{ $settings['app_name'] }}</div></div>
        <div class="card"><div class="label">Environment</div><div class="value">{{ $settings['environment'] }}</div></div>
        <div class="card"><div class="label">Base Currency</div><div class="value">{{ $settings['base_currency'] }}</div></div>
        <div class="card"><div class="label">Recon Threshold</div><div class="value money">${{ number_format($settings['reconciliation_threshold'], 2) }}</div></div>
    </section>

    <div class="grid two" style="margin-top:16px;">
        <section class="panel">
            <h2>Chart of Accounts</h2>
            <table>
                <thead><tr><th>Code</th><th>Name</th><th>Type</th><th>Currency</th></tr></thead>
                <tbody>
                    @forelse ($accounts as $account)
                        <tr>
                            <td>{{ $account->code }}</td>
                            <td>{{ $account->name }}</td>
                            <td>{{ $account->type }}</td>
                            <td>{{ $account->currency }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">No accounts configured. Run the database seeder.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="stack">
            <div class="panel">
                <h2>Processor Activity</h2>
                <table>
                    <thead><tr><th>Processor</th><th>Transactions</th></tr></thead>
                    <tbody>
                        @forelse ($processors as $processor)
                            <tr><td>{{ $processor->provider }}</td><td>{{ $processor->transaction_count }}</td></tr>
                        @empty
                            <tr><td colspan="2">No processors used yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="panel">
                <h2>Runtime Services</h2>
                <table>
                    <tbody>
                        <tr><td>Mail driver</td><td>{{ $settings['mail_driver'] }}</td></tr>
                        <tr><td>Queue connection</td><td>{{ $settings['queue_connection'] }}</td></tr>
                        <tr><td>Background assets</td><td>{{ file_exists(public_path('images/colour-palette.jpeg')) ? 'Ready' : 'Missing' }}</td></tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
