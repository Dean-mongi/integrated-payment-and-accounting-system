<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MaliHub</title>
    <style>
        * { box-sizing:border-box; }
        body {
            margin:0;
            min-height:100vh;
            font-family:Arial, Helvetica, sans-serif;
            background:linear-gradient(180deg, rgba(0,0,0,.68), rgba(0,0,0,.9)), url("{{ asset('images/colour-palette.jpeg') }}") center / cover fixed no-repeat;
            color:#f6f7f9;
        }
        .hero {
            min-height:100vh;
            display:grid;
            align-items:center;
            padding:28px clamp(16px, 5vw, 70px);
        }
        .wrap {
            width:min(1040px, 100%);
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:34px;
            align-items:center;
        }
        .logo {
            width:min(340px, 82vw);
            border-radius:18px;
            box-shadow:0 24px 70px rgba(0,0,0,.42);
        }
        h1 { margin:0; font-size:clamp(42px, 8vw, 86px); line-height:.95; }
        p { color:#cdd5df; font-size:18px; line-height:1.6; max-width:620px; }
        .actions { display:flex; gap:12px; flex-wrap:wrap; margin-top:24px; }
        a {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-height:44px;
            padding:12px 16px;
            border-radius:8px;
            text-decoration:none;
            font-weight:900;
        }
        .primary { background:linear-gradient(135deg, #55bf1a, #0f766e); color:#061407; }
        .secondary { border:1px solid rgba(255,255,255,.2); color:#fff; background:rgba(0,0,0,.38); }
        .metrics { display:grid; grid-template-columns:repeat(3, 1fr); gap:12px; margin-top:30px; }
        .metric { border:1px solid rgba(255,255,255,.14); border-radius:8px; padding:14px; background:rgba(0,0,0,.46); }
        .metric strong { display:block; font-size:22px; }
        .metric span { color:#a8b0bd; font-size:12px; font-weight:800; text-transform:uppercase; }
        @media (max-width: 820px) {
            .wrap { grid-template-columns:1fr; }
            .metrics { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
    <main class="hero">
        <div class="wrap">
            <div>
                <h1>MaliHub</h1>
                <p>Your Financial Hub. Grow Better. Manage invoices, receipts, mobile money, expenses, customers, suppliers, accounting reports, and audit controls in one responsive workspace.</p>
                <div class="actions">
                    <a class="primary" href="{{ route('login') }}">Login</a>
                    <a class="secondary" href="{{ route('register') }}">Register</a>
                </div>
                <div class="metrics" aria-label="System modules">
                    <div class="metric"><strong>360</strong><span>Business View</span></div>
                    <div class="metric"><strong>24/7</strong><span>Cash Flow</span></div>
                    <div class="metric"><strong>PDF</strong><span>Invoices</span></div>
                </div>
            </div>
            <img class="logo" src="{{ asset('images/malihub-logo.svg') }}" alt="MaliHub logo">
        </div>
    </main>
</body>
</html>
