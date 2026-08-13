<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend_assets/css/shopify-admin.css') }}">
</head>
<body class="sa-body">
<div class="sa-auth">
    <div class="sa-auth-card">
        <h1>Super Admin</h1>
        <p>Sign in to manage stores, domains, themes & apps.</p>
        @if(Session::has('msg'))
            <div class="sa-alert sa-alert-{{ Session::get('msg_type', 'error') }}" style="margin:0 0 14px;">{{ Session::get('msg') }}</div>
        @endif
        <form method="post" action="{{ route('superadmin.login.submit') }}">
            @csrf
            <div class="sa-field">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="sa-field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button class="sa-btn sa-btn-primary" type="submit" style="width:100%;">Sign in</button>
        </form>
    </div>
</div>
</body>
</html>
