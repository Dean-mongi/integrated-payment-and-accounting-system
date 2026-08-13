<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Integrated Payment Ledger') }}</title>
    {{-- This app can run from `php artisan serve` without a Node/Vite process. --}}
    <link rel="stylesheet" href="{{ asset('css/ledger.css') }}">
    <script src="{{ asset('js/ledger.js') }}" defer></script>
</head>
<body data-theme="dark">
<div class="app-shell" data-app-shell>
    <div class="drawer-backdrop" data-drawer-backdrop hidden></div>
    <aside class="sidebar" id="primary-navigation" aria-label="Primary navigation" data-sidebar>
        <div class="sidebar-head">
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark" aria-hidden="true">₿</span>
                <span><strong>Ledger</strong><small>Integrated payments</small></span>
            </a>
            <button class="icon-button drawer-close" type="button" aria-label="Close navigation" data-drawer-close>×</button>
        </div>

        <p class="menu-label">Workspace</p>
        <nav class="side-nav">
            @if (in_array(auth()->user()->role, ['admin', 'accountant'], true))
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><span aria-hidden="true">▦</span> Dashboard</a>
                <a href="{{ route('ledger') }}" class="{{ request()->routeIs('ledger') ? 'active' : '' }}"><span aria-hidden="true">≡</span> Ledger</a>
                <a href="{{ route('reconciliation') }}" class="{{ request()->routeIs('reconciliation') ? 'active' : '' }}"><span aria-hidden="true">✓</span> Reconciliation</a>
                <a href="{{ route('accounting.index') }}" class="{{ request()->routeIs('accounting.*') ? 'active' : '' }}"><span aria-hidden="true">⌘</span> Accounting</a>
                <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}"><span aria-hidden="true">−</span> Expenses</a>
                <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}"><span aria-hidden="true">♙</span> Customers</a>
            @endif
            <a href="{{ route('invoices.index') }}" class="{{ request()->routeIs('invoices.*') || request()->routeIs('receipts.*') ? 'active' : '' }}"><span aria-hidden="true">□</span> Invoices</a>
            @if (in_array(auth()->user()->role, ['admin', 'accountant'], true))
                <a href="{{ route('reports') }}" class="{{ request()->routeIs('reports') ? 'active' : '' }}"><span aria-hidden="true">↗</span> Reports</a>
                <a href="{{ route('analytics') }}" class="{{ request()->routeIs('analytics') ? 'active' : '' }}"><span aria-hidden="true">◔</span> Analytics</a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <span class="user-role">{{ ucfirst(auth()->user()->role) }} account</span>
            <form method="post" action="{{ route('logout') }}">@csrf <x-button variant="ghost" class="logout-button">Sign out</x-button></form>
        </div>
    </aside>

    <main class="app-main">
        <header class="topbar">
            <div class="topbar-title">
                <button class="icon-button menu-button" type="button" aria-label="Open navigation" aria-controls="primary-navigation" aria-expanded="false" data-drawer-open>☰</button>
                <div><p class="eyebrow">Integrated Payment Ledger</p><h1>@yield('page-title', 'Financial overview')</h1></div>
            </div>
            <div class="top-actions">
                <span class="date-chip">{{ now()->format('M j, Y') }}</span>
                <button class="theme-toggle" type="button" aria-pressed="false" data-theme-toggle><span aria-hidden="true">◐</span><span>Theme</span></button>
                <span class="notify-chip" aria-label="{{ \App\Models\SystemNotification::whereNull('read_at')->count() }} unread notifications">{{ \App\Models\SystemNotification::whereNull('read_at')->count() }}</span>
            </div>
        </header>
        <div class="container">
            @if (session('status')) <div class="alert alert-success" role="status">{{ session('status') }}</div> @endif
            @if ($errors->any()) <div class="alert alert-error" role="alert">{{ $errors->first() }}</div> @endif
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>
