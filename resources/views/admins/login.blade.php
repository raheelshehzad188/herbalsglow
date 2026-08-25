<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend_assets/css/shopify-admin.css') }}">
</head>
<body class="sa-body">
<div class="sa-auth">
    <div class="sa-auth-card">
        <h1>Log in</h1>
        <p>Continue to your store admin</p>
        @if(session('invalid'))
            <div class="sa-alert sa-alert-error" style="margin: 0 0 16px;">{{ session('invalid') }}</div>
        @endif
        <form action="{{ url('/admin/login') }}" method="post">
            @csrf
            <div class="sa-field">
                <label>Email</label>
                <input type="email" name="email" required autocomplete="username">
            </div>
            <div class="sa-field">
                <label>Password</label>
                <input type="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="sa-btn sa-btn-primary" style="width:100%">Log in</button>
        </form>
    </div>
</div>
</body>
</html>
