<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <style>
        :root {
            color-scheme: light;
            --ink:#172026;
            --muted:#66757f;
            --line:#d9e1e5;
            --paper:#f5f7f2;
            --panel:#ffffff;
            --accent:#0f766e;
            --accent-2:#9a5b13;
            --danger:#b42318;
            --success:#067a43;
            --shadow: 0 10px 28px rgba(13, 33, 38, 0.06);
        }

        * { box-sizing: border-box; }
        body { margin:0; font-family: Arial, Helvetica, sans-serif; background:var(--paper); color:var(--ink); }

        header {
            background:#12343b;
            color:#fff;
            padding:18px clamp(16px, 4vw, 48px);
            position: sticky;
            top:0;
            z-index: 20;
        }
        header .row { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; }
        header h1 { margin:0; font-size:clamp(20px, 3.4vw, 30px); line-height:1.1; font-weight:800; }
        header p { margin:6px 0 0; color:#cfe3e0; max-width:760px; font-size:14px; }

        nav { display:flex; gap:10px; flex-wrap:wrap; }
        nav a {
            display:inline-flex;
            align-items:center;
            padding:9px 12px;
            border:1px solid rgba(255,255,255,0.14);
            border-radius:8px;
            color:#fff;
            text-decoration:none;
            font-weight:700;
            font-size:13px;
            background: rgba(255,255,255,0.06);
        }
        nav a.active { background: rgba(255,255,255,0.16); }

        main { padding:22px clamp(16px, 4vw, 48px) 60px; }

        .container { max-width: 1160px; margin: 0 auto; }

        .status {
            margin-bottom:16px;
            padding:12px 14px;
            background:#e7f6ec;
            border:1px solid #b7dfc1;
            border-radius:10px;
            color:#125a2f;
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
            border-radius:12px;
            padding:16px;
            box-shadow: var(--shadow);
        }
        .label { color:var(--muted); font-size:12px; text-transform:uppercase; font-weight:900; letter-spacing:0; }
        .value { margin-top:8px; font-size:26px; font-weight:900; }
        .pill {
            display:inline-flex;
            padding:4px 9px;
            border-radius:999px;
            font-size:12px;
            font-weight:900;
            background:#eef5f4;
            color:#0f5d56;
        }
        .pill.bad { background:#fde8e5; color:var(--danger); }

        .panel {
            background:var(--panel);
            border:1px solid var(--line);
            border-radius:12px;
            padding:16px;
            box-shadow: var(--shadow);
        }

        h2 { margin: 0 0 14px; font-size:16px; font-weight: 900; }

        .row { display:flex; gap:12px; align-items:flex-start; }
        .split { display:grid; grid-template-columns: 1fr; gap:10px; }
        @media (min-width: 560px) {
            .split { grid-template-columns: 1fr 1fr; }
        }

        form { display:grid; gap:12px; }
        .field { display:grid; gap:6px; }
        label { font-size:13px; color:#34444c; font-weight:800; }

        input, select {
            width:100%;
            border:1px solid #b8c4ca;
            border-radius:10px;
            padding:10px 11px;
            background:#fff;
            color:var(--ink);
            font-size:14px;
        }

        button {
            border:0;
            border-radius:10px;
            padding:11px 14px;
            background:var(--accent);
            color:#fff;
            font-weight:900;
            cursor:pointer;
            font-size:14px;
        }
        button.secondary { background:#6f4f1f; }

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
            background:#fff;
            font-size:13px;
            font-weight:900;
            text-decoration:none;
        }
    </style>
</head>
<body>
<header>
    <div class="row">
        <div>
            <h1>{{ config('app.name') }}</h1>
            <p>Integrated Payments + Accounting: ledger posting, FX booking, and daily reconciliation.</p>
        </div>
        <nav>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('ledger') }}" class="{{ request()->routeIs('ledger') ? 'active' : '' }}">Ledger</a>
            <a href="{{ route('reconciliation') }}" class="{{ request()->routeIs('reconciliation') ? 'active' : '' }}">Reconciliation</a>
            <a href="{{ route('fees') }}" class="{{ request()->routeIs('fees') ? 'active' : '' }}">Fees</a>
            <a href="{{ route('currency') }}" class="{{ request()->routeIs('currency') ? 'active' : '' }}">Currency</a>
        </nav>
    </div>
</header>

<main>
    <div class="container">
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="status" style="background:#fff3f0;border-color:#f0b8ad;color:#8a1f11;">
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </div>
</main>
</body>
</html>

