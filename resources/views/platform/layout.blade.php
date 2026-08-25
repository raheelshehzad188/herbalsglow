<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $brand)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/saas-platform.css') }}">
</head>
<body class="sg-body">
<header class="sg-header">
    <div class="sg-wrap sg-header-inner">
        <a class="sg-logo" href="{{ platform_url() }}">{{ $settings['logo_text'] ?? $brand }}</a>
        <nav class="sg-nav">
            <a class="{{ ($page ?? '') === 'products' ? 'is-active' : '' }}" href="{{ platform_url('products') }}">Products</a>
            <a class="{{ ($page ?? '') === 'themes' ? 'is-active' : '' }}" href="{{ platform_url('themes') }}">Themes</a>
            <a class="{{ ($page ?? '') === 'apps' ? 'is-active' : '' }}" href="{{ platform_url('apps') }}">Apps</a>
            <a class="{{ ($page ?? '') === 'pricing' ? 'is-active' : '' }}" href="{{ platform_url('pricing') }}">Pricing</a>
        </nav>
        <div class="sg-header-actions">
            <a class="sg-link" href="{{ url('/admin/login') }}">{{ $settings['nav_signin'] ?? 'Sign in' }}</a>
            <a class="sg-btn sg-btn-dark" href="{{ platform_url('start') }}">{{ $settings['nav_start'] ?? 'Start free' }}</a>
        </div>
    </div>
</header>

@yield('content')

<section class="sg-wrap">
    <div class="sg-dark">
        <h2>{{ $settings['final_heading'] ?? 'Build the store your business deserves.' }}</h2>
        <div class="sg-hero-actions">
            <a class="sg-btn sg-btn-light" href="{{ platform_url('start') }}">{{ $settings['final_cta_primary'] ?? 'Start for free' }}</a>
            <a class="sg-btn sg-btn-ghost" href="mailto:{{ $settings['support_email'] ?? '' }}">{{ $settings['final_cta_secondary'] ?? 'Contact support' }}</a>
        </div>
    </div>
</section>

<footer class="sg-footer">
    <div class="sg-wrap sg-foot-grid">
        <div>
            <div class="sg-logo">{{ $settings['logo_text'] ?? $brand }}</div>
            <p class="sg-foot-about">{{ $settings['footer_about'] ?? '' }}</p>
            <div class="sg-hero-actions">
                <a class="sg-btn sg-btn-light" href="{{ platform_url('start') }}">Start free</a>
            </div>
        </div>
        <div>
            <h4>Sell</h4>
            <p><a href="{{ platform_url('products') }}">Online Store</a></p>
            <p><a href="{{ platform_url('products') }}">Restaurant ordering</a></p>
            <p><a href="{{ platform_url('products') }}">POS software</a></p>
            <p><a href="{{ platform_url('pricing') }}">Pricing</a></p>
        </div>
        <div>
            <h4>Build</h4>
            <p><a href="{{ platform_url('themes') }}">Theme gallery</a></p>
            <p><a href="{{ platform_url('apps') }}">Apps</a></p>
            <p><a href="{{ platform_url('start') }}">Start free</a></p>
        </div>
        <div>
            <h4>Company</h4>
            <p><a href="{{ url('/admin/login') }}">Sign in</a></p>
            <p><a href="{{ url('/superadmin/login') }}">Super admin</a></p>
            <p><a href="mailto:{{ $settings['support_email'] ?? '' }}">Email us</a></p>
        </div>
    </div>
    <div class="sg-wrap sg-copy">© {{ date('Y') }} {{ $brand }}. Super admin can edit every line of this site.</div>
</footer>
</body>
</html>
