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
        <div class="card"><div class="label">Business</div><div class="value" style="font-size:18px;">{{ $business?->name ?? 'MaliHub' }}</div><div class="tiny">{{ $business?->tagline ?? 'Your Financial Hub. Grow Better.' }}</div></div>
        <div class="card"><div class="label">Environment</div><div class="value">{{ $settings['environment'] }}</div></div>
        <div class="card"><div class="label">Base Currency</div><div class="value">{{ $settings['base_currency'] }}</div></div>
        <div class="card"><div class="label">Recon Threshold</div><div class="value money">${{ number_format($settings['reconciliation_threshold'], 2) }}</div></div>
    </section>

    <section class="panel" style="margin-top:16px;">
        <h2>Company Profile</h2>
        <div style="display:grid;grid-template-columns:96px 1fr;gap:14px;align-items:center;">
            <img src="{{ asset('images/malihub-logo.svg') }}" alt="MaliHub logo" style="width:96px;height:96px;object-fit:contain;background:#fff;border-radius:8px;">
            <table>
                <tbody>
                    <tr><td>Name</td><td>{{ $business?->name ?? 'MaliHub' }}</td></tr>
                    <tr><td>Email</td><td>{{ $business?->email ?? 'finance@malihub.local' }}</td></tr>
                    <tr><td>Phone</td><td>{{ $business?->phone ?? 'Not set' }}</td></tr>
                    <tr><td>Tax number</td><td>{{ $business?->tax_number ?? 'Not set' }}</td></tr>
                    <tr><td>Subscription</td><td>Business finance workspace</td></tr>
                    <tr><td>Integrations</td><td>Mobile money, bank, cash, email, WhatsApp, PDF print</td></tr>
                </tbody>
            </table>
        </div>
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
                        <tr><td>Payment data protection</td><td>Card details are not stored</td></tr>
                        <tr><td>Backup storage</td><td>storage/app/backups</td></tr>
                    </tbody>
                </table>
                <form method="post" action="{{ route('settings.backup') }}" style="margin-top:12px;">
                    @csrf
                    <button type="submit">Create backup now</button>
                </form>
            </div>
        </section>
    </div>

    <div class="grid two" style="margin-top:16px;">
        <section class="panel">
            <h2>Notifications</h2>
            <table>
                <thead><tr><th>Type</th><th>Message</th><th>Created</th></tr></thead>
                <tbody>
                    @forelse ($notifications as $notification)
                        <tr>
                            <td>{{ str_replace('_', ' ', $notification->type) }}</td>
                            <td><strong>{{ $notification->title }}</strong><div class="tiny">{{ $notification->message }}</div></td>
                            <td>{{ $notification->created_at->format('M j, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No notifications yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="panel">
            <h2>Security Audit Logs</h2>
            <table>
                <thead><tr><th>Event</th><th>User</th><th>Time</th></tr></thead>
                <tbody>
                    @forelse ($auditLogs as $log)
                        <tr>
                            <td>{{ $log->event }}</td>
                            <td>{{ $log->user?->email ?? 'System' }}</td>
                            <td>{{ $log->created_at->format('M j, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No audit events yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>
@endsection
