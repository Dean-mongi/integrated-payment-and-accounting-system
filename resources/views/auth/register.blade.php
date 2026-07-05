<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - MaliHub</title>
    <style>
        body { margin:0; min-height:100vh; display:grid; place-items:center; font-family:Arial, Helvetica, sans-serif; background:linear-gradient(180deg, rgba(0,0,0,.76), rgba(0,0,0,.9)), url("{{ asset('images/colour-palette.jpeg') }}") center / cover fixed no-repeat; color:#f6f7f9; }
        .card { width:min(460px, calc(100vw - 32px)); padding:26px; border:1px solid rgba(255,255,255,.14); border-radius:14px; background:rgba(0,0,0,.78); box-shadow:0 22px 60px rgba(0,0,0,.48); }
        .brand { display:flex; gap:12px; align-items:center; margin-bottom:18px; }
        .brand img { width:64px; height:64px; object-fit:contain; background:#fff; border-radius:10px; }
        h1 { margin:0 0 4px; font-size:24px; }
        p { margin:0; color:#a8b0bd; }
        form { display:grid; gap:14px; }
        label { display:grid; gap:7px; font-weight:800; font-size:13px; }
        input { width:100%; box-sizing:border-box; border:1px solid rgba(255,255,255,.18); border-radius:10px; padding:11px; background:rgba(0,0,0,.58); color:#fff; }
        button { border:0; border-radius:10px; padding:12px 14px; background:linear-gradient(135deg, #55bf1a, #0f766e); color:#02140b; font-weight:900; cursor:pointer; }
        .error { margin-bottom:14px; color:#fecaca; font-weight:800; }
        a { color:#86efac; font-weight:800; text-decoration:none; font-size:13px; }
    </style>
</head>
<body>
    <section class="card">
        <div class="brand">
            <img src="{{ asset('images/malihub-logo.svg') }}" alt="MaliHub logo">
            <div><h1>Create MaliHub account</h1><p>Start with secure customer access.</p></div>
        </div>
        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        <form method="post" action="{{ route('register.store') }}">
            @csrf
            <label>Name<input name="name" value="{{ old('name') }}" required autofocus></label>
            <label>Email<input name="email" type="email" value="{{ old('email') }}" required></label>
            <label>Password<input name="password" type="password" required></label>
            <label>Confirm password<input name="password_confirmation" type="password" required></label>
            <button type="submit">Register</button>
        </form>
        <p style="margin-top:16px;"><a href="{{ route('login') }}">Already have an account?</a></p>
    </section>
</body>
</html>
