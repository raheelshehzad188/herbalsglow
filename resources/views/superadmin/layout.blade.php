<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin') — Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend_assets/css/shopify-admin.css') }}">
    @stack('styles')
</head>
<body class="sa-body">
@php $admin = Session::get('admin'); @endphp
<div class="sa-shell">
    <aside class="sa-sidebar">
        <div class="sa-brand">
            <span class="sa-brand-mark">S</span>
            <div>
                <strong>Super Admin</strong>
                <small>Multi-store platform</small>
            </div>
        </div>
        <nav class="sa-nav">
            <a class="sa-nav-item @yield('nav_dashboard')" href="{{ route('superadmin.dashboard') }}">Dashboard</a>
            <a class="sa-nav-item @yield('nav_stores')" href="{{ route('superadmin.stores') }}">Stores</a>
            <a class="sa-nav-item" href="{{ url('/admin/dashboard') }}">Store Admin</a>
        </nav>
        <div class="sa-sidebar-foot">
            <div class="sa-user">{{ $admin->email ?? 'Admin' }}</div>
            <a href="{{ route('superadmin.logout') }}">Logout</a>
        </div>
    </aside>
    <main class="sa-main">
        <header class="sa-topbar">
            <div>
                <h1>@yield('page_title', 'Dashboard')</h1>
                <p class="sa-subtitle">@yield('page_subtitle', 'Manage domains, themes & integrations')</p>
            </div>
            <div class="sa-topbar-actions">
                @yield('page_actions')
            </div>
        </header>

        @if(Session::has('msg'))
            <div class="sa-alert sa-alert-{{ Session::get('msg_type', 'success') }}">
                {{ Session::get('msg') }}
            </div>
        @endif

        <div class="sa-content">
            @yield('content')
        </div>
    </main>
</div>
@stack('scripts')
</body>
</html>
