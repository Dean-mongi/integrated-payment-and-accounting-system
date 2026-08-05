<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <style>
        :root {
            color-scheme: dark;
            --ink:#f6f7f9;
            --muted:#a8b0bd;
            --line:rgba(255,255,255,0.14);
            --paper:#06080c;
            --paper-soft:#0c1118;
            --panel:rgba(13, 18, 26, 0.86);
            --panel-strong:rgba(8, 12, 18, 0.96);
            --field:rgba(4, 8, 13, 0.72);
            --chip:rgba(255,255,255,0.07);
            --accent:#22c55e;
            --accent-strong:#16a34a;
            --accent-ink:#04130a;
            --accent-2:#38bdf8;
            --danger:#f87171;
            --success:#34d399;
            --shadow: 0 20px 50px rgba(0, 0, 0, 0.36);
        }

        [data-theme="light"] {
            color-scheme: light;
            --ink:#111827;
            --muted:#5b6676;
            --line:rgba(17,24,39,0.13);
            --paper:#f7fafc;
            --paper-soft:#eef4f8;
            --panel:rgba(255,255,255,0.9);
            --panel-strong:#ffffff;
            --field:#ffffff;
            --chip:rgba(17,24,39,0.055);
            --accent:#16a34a;
            --accent-strong:#15803d;
            --accent-ink:#ffffff;
            --accent-2:#0284c7;
            --shadow: 0 18px 44px rgba(15, 23, 42, 0.1);
        }

        * { box-sizing: border-box; }
        body {
            margin:0;
            min-height:100vh;
            font-family: Arial, Helvetica, sans-serif;
            background:
                linear-gradient(135deg, var(--paper), var(--paper-soft)),
                var(--paper);
            color:var(--ink);
        }
        body::before {
            content:"";
            position:fixed;
            inset:0;
            pointer-events:none;
            background:linear-gradient(180deg, rgba(56,189,248,0.08), transparent 38%);
            z-index:-1;
        }

        header {
            background:var(--panel-strong);
            color:var(--ink);
            padding:18px clamp(16px, 4vw, 48px);
            position: sticky;
            top:0;
            z-index: 20;
            border-bottom:1px solid var(--line);
            backdrop-filter: blur(14px);
        }
        header .row { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; }
        header h1 { margin:0; font-size:clamp(20px, 3.4vw, 30px); line-height:1.1; font-weight:800; }
        header p { margin:6px 0 0; color:var(--muted); max-width:760px; font-size:14px; }

        nav { display:flex; gap:10px; flex-wrap:wrap; }
        nav a {
            display:inline-flex;
            align-items:center;
            padding:9px 12px;
            border:1px solid var(--line);
            border-radius:8px;
            color:var(--ink);
            text-decoration:none;
            font-weight:700;
            font-size:13px;
            background: var(--chip);
        }
        nav a.active {
            background: rgba(34,197,94,0.18);
            border-color: rgba(34,197,94,0.48);
            color:var(--ink);
        }

        main { padding:22px clamp(16px, 4vw, 48px) 60px; }

        .container { max-width: 1160px; margin: 0 auto; }

        .status {
            margin-bottom:16px;
            padding:12px 14px;
            background:rgba(5, 46, 22, 0.86);
            border:1px solid rgba(52, 211, 153, 0.42);
            border-radius:10px;
            color:#d1fae5;
            font-weight:700;
        }

        .grid {
            display:grid;
            grid-template-columns: 1fr;
            gap:18px;
        }
        @media (min-width: 980px) {
            .grid.two { grid-template-columns: 420px 1fr; }
            .grid.cards { grid-template-columns: repeat(12, 1fr); }
        }

        .cards {
            display:grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap:12px;
        }
        .card {
            background:var(--panel);
            border:1px solid var(--line);
            border-radius:8px;
            padding:16px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px);
        }
        .label { color:var(--muted); font-size:12px; text-transform:uppercase; font-weight:900; letter-spacing:0; }
        .value { margin-top:8px; font-size:26px; font-weight:900; }
        .pill {
            display:inline-flex;
            padding:4px 9px;
            border-radius:999px;
            font-size:12px;
            font-weight:900;
            background:rgba(34,197,94,0.16);
            color:#bbf7d0;
            border:1px solid rgba(34,197,94,0.24);
        }
        .pill.bad {
            background:rgba(248,113,113,0.16);
            color:#fecaca;
            border-color:rgba(248,113,113,0.35);
        }

        .panel {
            background:var(--panel);
            border:1px solid var(--line);
            border-radius:8px;
            padding:16px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px);
        }

        h2 { margin: 0 0 14px; font-size:16px; font-weight: 900; }

        .row { display:flex; gap:12px; align-items:flex-start; }
        .split { display:grid; grid-template-columns: 1fr; gap:10px; }
        @media (min-width: 560px) {
            .split { grid-template-columns: 1fr 1fr; }
        }

        form { display:grid; gap:12px; }
        .inline-form { display:inline; }
        .field { display:grid; gap:6px; }
        label { font-size:13px; color:var(--ink); font-weight:800; }

        input, select {
            width:100%;
            border:1px solid rgba(255,255,255,0.18);
            border-radius:10px;
            padding:10px 11px;
            background:var(--field);
            color:var(--ink);
            font-size:14px;
        }
        input:focus, select:focus {
            outline:2px solid rgba(34,197,94,0.42);
            border-color:rgba(34,197,94,0.72);
        }
        input::placeholder { color:#78808d; }

        button {
            border:0;
            border-radius:10px;
            padding:11px 14px;
            background:var(--accent);
            color:var(--accent-ink);
            font-weight:900;
            cursor:pointer;
            font-size:14px;
        }
        button.secondary { background:var(--accent-2); color:#fff; }

        table { width:100%; border-collapse: collapse; font-size:14px; }
        th, td { padding:11px 8px; border-bottom:1px solid var(--line); text-align:left; vertical-align:top; }
        th { color:var(--muted); font-size:12px; text-transform:uppercase; font-weight:900; }

        .money { font-variant-numeric:tabular-nums; white-space:nowrap; }
        .tiny { color:var(--muted); font-size:12px; }

        .responsive-table td:nth-child(3), .responsive-table th:nth-child(3) { white-space:nowrap; }

        .page-head {
            display:flex;
            justify-content:space-between;
            gap:16px;
            align-items:flex-start;
            margin-bottom:18px;
            flex-wrap:wrap;
        }
        .page-head h2 { margin:0; font-size:22px; }
        .page-head p { margin:6px 0 0; color:var(--muted); max-width:760px; font-size:14px; line-height:1.5; }
        .stack { display:grid; gap:12px; }
        .toolbar { display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
        .link-button {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-height:38px;
            padding:9px 12px;
            border-radius:8px;
            border:1px solid var(--line);
            color:var(--ink);
            background:var(--chip);
            font-size:13px;
            font-weight:900;
            text-decoration:none;
        }

        .app-shell {
            display:grid;
            grid-template-columns: 232px minmax(0, 1fr);
            min-height:100vh;
        }
        .sidebar {
            position:sticky;
            top:0;
            height:100vh;
            overflow:auto;
            padding:20px 14px;
            background:var(--panel-strong);
            border-right:1px solid var(--line);
            backdrop-filter: blur(16px);
        }
        .brand {
            display:flex;
            align-items:center;
            gap:10px;
            padding:0 10px 20px;
        }
        .brand-logo {
            width:44px;
            height:44px;
            object-fit:contain;
            border-radius:8px;
            background:#fff;
        }
        .brand-mark {
            display:grid;
            place-items:center;
            width:36px;
            height:36px;
            border-radius:10px;
            background:linear-gradient(135deg, var(--accent), var(--accent-2));
            color:var(--accent-ink);
            font-size:14px;
            font-weight:900;
        }
        .brand strong { display:block; font-size:20px; line-height:1; }
        .brand span { display:block; color:var(--muted); font-size:12px; margin-top:2px; }
        .search-box {
            display:flex;
            align-items:center;
            gap:8px;
            margin:0 0 18px;
            padding:10px 11px;
            border:1px solid var(--line);
            border-radius:9px;
            background:var(--chip);
            color:var(--muted);
            font-size:13px;
        }
        .menu-label {
            margin:20px 4px 8px;
            color:var(--muted);
            font-size:11px;
            font-weight:900;
            text-transform:uppercase;
        }
        .sidebar nav {
            display:grid;
            gap:6px;
        }
        .sidebar nav a {
            display:flex;
            align-items:center;
            gap:10px;
            width:100%;
            padding:11px 12px;
            border:1px solid transparent;
            border-radius:8px;
            background:transparent;
            color:var(--ink);
            font-size:14px;
            font-weight:800;
        }
        .sidebar nav a.active {
            background:linear-gradient(135deg, var(--accent), var(--accent-2));
            border-color:rgba(34,197,94,0.35);
            color:var(--accent-ink);
        }
        .nav-icon {
            display:grid;
            place-items:center;
            width:20px;
            height:20px;
            border-radius:6px;
            border:1px solid currentColor;
            font-size:11px;
            font-weight:900;
        }
        .app-main {
            min-width:0;
            padding:18px clamp(16px, 3vw, 34px) 44px;
        }
        .table-scroll {
            width:100%;
            overflow-x:auto;
            -webkit-overflow-scrolling:touch;
        }
        .table-scroll table { min-width:680px; }
        .panel > table,
        .card > table {
            display:block;
            width:100%;
            overflow-x:auto;
            -webkit-overflow-scrolling:touch;
        }
        .topbar {
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:16px;
            margin-bottom:18px;
        }
        .topbar .eyebrow { margin:0 0 6px; color:var(--muted); font-size:13px; }
        .topbar h1 { margin:0; font-size:20px; line-height:1.25; }
        .top-actions {
            display:flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
        }
        .date-chip, .notify-chip {
            display:inline-flex;
            align-items:center;
            min-height:38px;
            padding:9px 12px;
            border:1px solid var(--line);
            border-radius:8px;
            background:var(--chip);
            color:var(--ink);
            font-weight:800;
            font-size:13px;
        }
        .notify-chip {
            width:38px;
            justify-content:center;
            padding:0;
        }
        .theme-toggle {
            display:inline-flex;
            align-items:center;
            gap:8px;
            min-height:38px;
            border:1px solid var(--line);
            border-radius:8px;
            padding:0 12px;
            background:var(--chip);
            color:var(--ink);
            font-weight:900;
            font-size:13px;
        }
        .theme-toggle input {
            width:34px;
            height:18px;
            margin:0;
            accent-color:var(--accent);
        }

        @media (max-width: 920px) {
            .app-shell { grid-template-columns:1fr; }
            .sidebar {
                position:relative;
                height:auto;
                border-right:0;
                border-bottom:1px solid var(--line);
            }
            .sidebar nav { grid-template-columns:repeat(auto-fit, minmax(132px, 1fr)); }
            .topbar { flex-direction:column; }
        }
        @media (max-width: 560px) {
            .app-main { padding:16px 12px 36px; }
            .sidebar { padding:14px 10px; }
            .brand { padding:0 4px 14px; }
            .brand strong { font-size:18px; }
            .brand span, .search-box { display:none; }
            .sidebar nav { display:flex; overflow-x:auto; gap:8px; padding-bottom:4px; }
            .sidebar nav a { flex:0 0 auto; min-width:116px; padding:10px; font-size:12px; }
            .top-actions { width:100%; }
            .date-chip, .theme-toggle { flex:1; justify-content:center; }
            .value { font-size:22px; }
            th, td { padding:9px 7px; }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" aria-label="Primary navigation">
        <div class="brand">
            <img class="brand-logo" src="{{ asset('images/malihub-logo.svg') }}" alt="MaliHub logo">
            <div>
                <strong>MaliHub</strong>
                <span>Your Financial Hub. Grow Better.</span>
            </div>
        </div>

        <div class="search-box">
            <span class="nav-icon">S</span>
            <span>Search...</span>
        </div>

        <div class="menu-label">Main menu</div>
        <nav>
            @if (in_array(auth()->user()->role, ['admin', 'accountant'], true))
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="nav-icon">D</span>Dashboard</a>
                <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}"><span class="nav-icon">C</span>Customers</a>
                <a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}"><span class="nav-icon">S</span>Suppliers</a>
                <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}"><span class="nav-icon">P</span>Products</a>
                <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}"><span class="nav-icon">E</span>Expenses</a>
                <a href="{{ route('accounting.index') }}" class="{{ request()->routeIs('accounting.*') ? 'active' : '' }}"><span class="nav-icon">G</span>Accounting</a>
                <a href="{{ route('ledger') }}" class="{{ request()->routeIs('ledger') ? 'active' : '' }}"><span class="nav-icon">L</span>Ledger</a>
                <a href="{{ route('reconciliation') }}" class="{{ request()->routeIs('reconciliation') ? 'active' : '' }}"><span class="nav-icon">R</span>Reconciliation</a>
                <a href="{{ route('fees') }}" class="{{ request()->routeIs('fees') ? 'active' : '' }}"><span class="nav-icon">F</span>Fees</a>
                <a href="{{ route('currency') }}" class="{{ request()->routeIs('currency') ? 'active' : '' }}"><span class="nav-icon">X</span>Currency</a>
            @endif
            <a href="{{ route('invoices.index') }}" class="{{ request()->routeIs('invoices.*') || request()->routeIs('receipts.*') ? 'active' : '' }}"><span class="nav-icon">I</span>Invoices</a>
        </nav>

        <div class="menu-label">System</div>
        <nav>
            @if (in_array(auth()->user()->role, ['admin', 'accountant'], true))
                <a href="{{ route('analytics') }}" class="{{ request()->routeIs('analytics') ? 'active' : '' }}"><span class="nav-icon">A</span>Analytics</a>
                <a href="{{ route('reports') }}" class="{{ request()->routeIs('reports') ? 'active' : '' }}"><span class="nav-icon">T</span>Reports</a>
            @endif
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('settings') }}" class="{{ request()->routeIs('settings') ? 'active' : '' }}"><span class="nav-icon">S</span>Settings</a>
            @endif
            <a href="{{ route('invoices.index') }}"><span class="nav-icon">H</span>Support</a>
            <form class="inline-form" method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="width:100%;background:var(--chip);color:var(--ink);text-align:left;">Logout</button>
            </form>
        </nav>

    </aside>

    <main class="app-main">
        <div class="topbar">
            <div>
                <p class="eyebrow">MaliHub</p>
                <h1>Integrated Payment and Accounting System</h1>
            </div>
            <div class="top-actions">
                <span class="date-chip">{{ ucfirst(auth()->user()->role) }}</span>
                <span class="date-chip">{{ now()->format('M j, Y') }}</span>
                <label class="theme-toggle" for="theme-toggle">
                    <span>Light</span>
                    <input id="theme-toggle" type="checkbox" aria-label="Toggle light theme">
                </label>
                <span class="notify-chip">{{ \App\Models\SystemNotification::whereNull('read_at')->count() }}</span>
            </div>
        </div>

        <div class="container">
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="status" style="background:rgba(69,10,10,0.9);border-color:rgba(248,113,113,0.42);color:#fee2e2;">
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
        </div>
    </main>
</div>
<script>
    (() => {
        const storageKey = 'malihub-theme';
        const root = document.documentElement;
        const toggle = document.getElementById('theme-toggle');
        const preferred = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
        const saved = localStorage.getItem(storageKey) || preferred;

        root.dataset.theme = saved;
        if (toggle) {
            toggle.checked = saved === 'light';
            toggle.addEventListener('change', () => {
                const theme = toggle.checked ? 'light' : 'dark';
                root.dataset.theme = theme;
                localStorage.setItem(storageKey, theme);
            });
        }
    })();
</script>
</body>
</html>

