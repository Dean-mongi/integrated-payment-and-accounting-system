<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MaliHub') }}</title>
    <link rel="stylesheet" href="{{ asset('css/ledger.css') }}">
    <link rel="stylesheet" href="{{ asset('css/malihub.css') }}">
    <script src="{{ asset('js/ledger.js') }}" defer></script>
</head>
<body data-theme="dark">
<div class="malihub-shell" data-app-shell>
    <div class="drawer-backdrop" data-drawer-backdrop hidden></div>
    <header class="site-header">
        <a class="malihub-brand" href="{{ route('dashboard') }}"><img src="{{ asset('images/malihub-logo.svg') }}" alt="MaliHub logo"><strong>MaliHub</strong></a>
        <button class="mobile-menu" type="button" aria-label="Open navigation" aria-controls="primary-navigation" aria-expanded="false" data-drawer-open>☰</button>
        <nav class="main-navigation" id="primary-navigation" aria-label="Primary navigation" data-sidebar>
            <button class="drawer-close" type="button" aria-label="Close navigation" data-drawer-close>×</button>
            @if (in_array(auth()->user()->role, ['admin', 'accountant'], true))
                <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="{{ request()->routeIs('ledger') ? 'active' : '' }}" href="{{ route('ledger') }}">Transactions</a>
                <a class="{{ request()->routeIs('accounting.*') ? 'active' : '' }}" href="{{ route('accounting.index') }}">Accounts</a>
            @endif
            <a class="{{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">Invoices</a>
            @if (in_array(auth()->user()->role, ['admin', 'accountant'], true))
                <a class="{{ request()->routeIs('analytics') ? 'active' : '' }}" href="{{ route('analytics') }}">Analytics</a>
            @endif
            @if (auth()->user()->role === 'admin')<a class="settings-link {{ request()->routeIs('settings') ? 'active' : '' }}" href="{{ route('settings') }}">Settings</a>@endif
            <form method="post" action="{{ route('logout') }}">@csrf <button class="nav-signout" type="submit">Sign out</button></form>
        </nav>
    </header>
    <main class="malihub-main">
        @if (session('status')) <div class="alert alert-success" role="status">{{ session('status') }}</div> @endif
        @if ($errors->any()) <div class="alert alert-error" role="alert">{{ $errors->first() }}</div> @endif
        @yield('content')
    </main>
</div>
</body>
</html>
