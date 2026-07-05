<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - MaliHub</title>
    <style>
        * { box-sizing:border-box; }
        body {
            margin:0;
            min-height:100vh;
            display:grid;
            place-items:center;
            padding:24px 14px;
            font-family:Arial, Helvetica, sans-serif;
            background:
                linear-gradient(120deg, rgba(0,27,17,.82), rgba(0,0,0,.92)),
                url("{{ asset('images/colour-palette.jpeg') }}") center / cover fixed no-repeat;
            color:#f6f7f9;
        }
        .auth-shell {
            width:min(1040px, 100%);
            min-height:620px;
            display:grid;
            grid-template-columns:minmax(0, .92fr) minmax(360px, .78fr);
            border:1px solid rgba(255,255,255,.14);
            border-radius:18px;
            overflow:hidden;
            background:rgba(0,0,0,.66);
            box-shadow:0 28px 80px rgba(0,0,0,.52);
            backdrop-filter:blur(18px);
        }
        .brand-panel {
            padding:34px;
            display:grid;
            align-content:space-between;
            gap:28px;
            background:
                linear-gradient(180deg, rgba(255,255,255,.98), rgba(235,250,240,.94));
            color:#073b24;
        }
        .brand-top { display:flex; align-items:center; gap:14px; }
        .brand-top img { width:78px; height:78px; object-fit:contain; border-radius:12px; }
        .brand-top strong { display:block; font-size:30px; line-height:1; }
        .brand-top span { display:block; margin-top:6px; color:#256b42; font-weight:800; font-size:13px; }
        .brand-copy h1 { margin:0 0 14px; font-size:clamp(34px, 5vw, 58px); line-height:.98; color:#06351f; }
        .brand-copy p { margin:0; max-width:560px; color:#315743; font-size:16px; line-height:1.7; }
        .metric-grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:10px; }
        .metric {
            min-height:92px;
            padding:14px;
            border:1px solid rgba(7,59,36,.14);
            border-radius:8px;
            background:rgba(255,255,255,.68);
        }
        .metric strong { display:block; font-size:22px; color:#073b24; }
        .metric span { color:#44725a; font-size:12px; font-weight:900; text-transform:uppercase; }
        .login-panel {
            padding:34px;
            display:grid;
            align-content:center;
            background:rgba(2,6,5,.74);
        }
        .panel-head { margin-bottom:24px; }
        .eyebrow { margin:0 0 8px; color:#86efac; font-size:12px; font-weight:900; text-transform:uppercase; }
        h2 { margin:0 0 8px; font-size:28px; }
        p { margin:0; color:#a8b0bd; line-height:1.55; }
        form { display:grid; gap:15px; }
        label { display:grid; gap:7px; color:#dce7df; font-weight:900; font-size:13px; }
        input {
            width:100%;
            border:1px solid rgba(255,255,255,.18);
            border-radius:10px;
            padding:13px 12px;
            background:rgba(0,0,0,.48);
            color:#fff;
            font-size:15px;
        }
        input:focus {
            outline:2px solid rgba(85,191,26,.36);
            border-color:rgba(134,239,172,.7);
        }
        .form-row {
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
        }
        .remember {
            display:flex;
            align-items:center;
            gap:8px;
            color:#dce7df;
            font-size:13px;
            font-weight:800;
        }
        .remember input { width:auto; }
        button {
            width:100%;
            border:0;
            border-radius:10px;
            padding:13px 16px;
            background:linear-gradient(135deg, #55bf1a, #0f766e);
            color:#031408;
            font-weight:900;
            cursor:pointer;
            font-size:15px;
        }
        .error {
            margin-bottom:14px;
            padding:11px 12px;
            border:1px solid rgba(248,113,113,.42);
            border-radius:10px;
            background:rgba(69,10,10,.58);
            color:#fecaca;
            font-weight:800;
        }
        .auth-links {
            display:flex;
            justify-content:space-between;
            gap:12px;
            flex-wrap:wrap;
            margin-top:18px;
        }
        a { color:#86efac; font-weight:900; text-decoration:none; font-size:13px; }
        .secure-note {
            margin-top:22px;
            padding:12px;
            border:1px solid rgba(255,255,255,.12);
            border-radius:8px;
            background:rgba(255,255,255,.04);
            color:#cdd5df;
            font-size:12px;
            line-height:1.55;
        }
        @media (max-width: 900px) {
            .auth-shell { grid-template-columns:1fr; min-height:auto; }
            .brand-panel { padding:24px; }
            .login-panel { padding:24px; }
        }
        @media (max-width: 560px) {
            body { padding:0; align-items:stretch; }
            .auth-shell { min-height:100vh; border:0; border-radius:0; }
            .metric-grid { grid-template-columns:1fr; }
            .brand-copy h1 { font-size:34px; }
            .form-row, .auth-links { display:grid; justify-content:stretch; }
        }
    </style>
</head>
<body>
    <main class="auth-shell">
        <section class="brand-panel" aria-label="MaliHub brand">
            <div class="brand-top">
                <img src="{{ asset('images/malihub-logo.svg') }}" alt="MaliHub logo">
                <div>
                    <strong>MaliHub</strong>
                    <span>Your Financial Hub. Grow Better.</span>
                </div>
            </div>
            <div class="brand-copy">
                <h1>Finance control starts here.</h1>
                <p>Access invoices, payments, receipts, expenses, ledger reports, and business performance from one protected MaliHub workspace.</p>
            </div>
            <div class="metric-grid" aria-label="Workspace highlights">
                <div class="metric"><strong>Ledger</strong><span>Accounting</span></div>
                <div class="metric"><strong>Cash</strong><span>Flow</span></div>
                <div class="metric"><strong>Audit</strong><span>Logs</span></div>
            </div>
        </section>

        <section class="login-panel">
            <div class="panel-head">
                <p class="eyebrow">Secure access</p>
                <h2>Sign in to MaliHub</h2>
                <p>Use your registered email and password to continue.</p>
            </div>

            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

            <form method="post" action="{{ route('login.store') }}">
                @csrf
                <label for="email">Email address
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                </label>
                <label for="password">Password
                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                </label>
                <div class="form-row">
                    <label class="remember">
                        <input name="remember" type="checkbox"> Remember me
                    </label>
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                </div>
                <button type="submit">Sign in</button>
            </form>

            <div class="auth-links">
                <span style="color:#a8b0bd;font-size:13px;">New user?</span>
                <a href="{{ route('register') }}">Create a secure account</a>
            </div>
            <div class="secure-note">MaliHub keeps this sign-in focused on your finance workspace only. No demo credentials are shown on this page.</div>
        </section>
    </main>
</body>
</html>
