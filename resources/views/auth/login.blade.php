<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name') }}</title>
    <style>
        body {
            margin:0;
            min-height:100vh;
            display:grid;
            place-items:center;
            font-family:Arial, Helvetica, sans-serif;
            background:linear-gradient(180deg, rgba(0,0,0,.76), rgba(0,0,0,.9)), url("{{ asset('images/colour-palette.jpeg') }}") center / cover fixed no-repeat;
            color:#f6f7f9;
        }
        .auth-wrap {
            width:min(920px, calc(100vw - 32px));
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:18px;
            align-items:stretch;
        }
        .brand-panel,
        .login-card {
            width:min(420px, calc(100vw - 32px));
            padding:26px;
            border:1px solid rgba(255,255,255,.14);
            border-radius:14px;
            background:rgba(0,0,0,.78);
            box-shadow:0 22px 60px rgba(0,0,0,.48);
        }
        .brand-panel { display:grid; align-content:center; justify-items:center; text-align:center; background:rgba(255,255,255,.94); color:#073b24; }
        .brand-panel img { width:100%; max-width:320px; }
        h1 { margin:0 0 6px; font-size:24px; }
        p { margin:0 0 22px; color:#a8b0bd; }
        form { display:grid; gap:14px; }
        label { display:grid; gap:7px; font-weight:800; font-size:13px; }
        input {
            width:100%;
            box-sizing:border-box;
            border:1px solid rgba(255,255,255,.18);
            border-radius:10px;
            padding:11px;
            background:rgba(0,0,0,.58);
            color:#fff;
        }
        button {
            border:0;
            border-radius:10px;
            padding:12px 14px;
            background:linear-gradient(135deg, #22c55e, #0f766e);
            color:#02140b;
            font-weight:900;
            cursor:pointer;
        }
        .error { margin-bottom:14px; color:#fecaca; font-weight:800; }
        .hint { margin-top:18px; color:#a8b0bd; font-size:12px; line-height:1.5; }
        .auth-links { display:flex; gap:12px; flex-wrap:wrap; margin-top:14px; }
        a { color:#86efac; font-weight:800; text-decoration:none; font-size:13px; }
        @media (max-width: 860px) {
            body { padding:16px 0; }
            .auth-wrap { grid-template-columns:1fr; justify-items:center; }
            .brand-panel { display:none; }
        }
    </style>
</head>
<body>
    <main class="auth-wrap">
        <section class="brand-panel" aria-label="MaliHub brand">
            <img src="{{ asset('images/malihub-logo.svg') }}" alt="MaliHub logo">
        </section>

    <section class="login-card">
        <h1>MaliHub</h1>
        <p>Your Financial Hub. Grow Better.</p>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ route('login.store') }}">
            @csrf
            <label>Email
                <input name="email" type="email" value="{{ old('email', 'admin@example.com') }}" required autofocus>
            </label>
            <label>Password
                <input name="password" type="password" value="password" required>
            </label>
            <label style="display:flex;align-items:center;gap:8px;">
                <input name="remember" type="checkbox" style="width:auto;"> Remember me
            </label>
            <button type="submit">Login</button>
        </form>

        <div class="hint">
            Demo users: admin@example.com, accountant@example.com, cashier@example.com, customer@example.com. Password: password.
        </div>
        <div class="auth-links">
            <a href="{{ route('register') }}">Create account</a>
            <a href="{{ route('password.request') }}">Forgot password?</a>
            <a href="{{ route('welcome') }}">Welcome page</a>
        </div>
    </section>
    </main>
</body>
</html>
