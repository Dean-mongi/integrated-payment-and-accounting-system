<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - MaliHub</title>
    <style>
        body { margin:0; min-height:100vh; display:grid; place-items:center; font-family:Arial, Helvetica, sans-serif; background:linear-gradient(180deg, rgba(0,0,0,.76), rgba(0,0,0,.9)), url("{{ asset('images/colour-palette.jpeg') }}") center / cover fixed no-repeat; color:#f6f7f9; }
        .card { width:min(440px, calc(100vw - 32px)); padding:26px; border:1px solid rgba(255,255,255,.14); border-radius:14px; background:rgba(0,0,0,.78); box-shadow:0 22px 60px rgba(0,0,0,.48); }
        img { width:86px; height:86px; object-fit:contain; background:#fff; border-radius:12px; margin-bottom:16px; }
        h1 { margin:0 0 8px; font-size:24px; }
        p { color:#a8b0bd; line-height:1.6; }
        label { display:grid; gap:7px; font-weight:800; font-size:13px; margin:16px 0 12px; }
        input { width:100%; box-sizing:border-box; border:1px solid rgba(255,255,255,.18); border-radius:10px; padding:11px; background:rgba(0,0,0,.58); color:#fff; }
        button { border:0; border-radius:10px; padding:12px 14px; background:linear-gradient(135deg, #55bf1a, #0f766e); color:#02140b; font-weight:900; cursor:pointer; }
        a { color:#86efac; font-weight:800; text-decoration:none; font-size:13px; }
    </style>
</head>
<body>
    <section class="card">
        <img src="{{ asset('images/malihub-logo.svg') }}" alt="MaliHub logo">
        <h1>Reset access</h1>
        <p>Password email delivery is ready for mail configuration. Enter an address so the support team can verify and issue a reset.</p>
        <form method="get" action="{{ route('login') }}">
            <label>Email<input name="email" type="email" required autofocus></label>
            <button type="submit">Continue</button>
        </form>
        <p><a href="{{ route('login') }}">Back to login</a></p>
    </section>
</body>
</html>
