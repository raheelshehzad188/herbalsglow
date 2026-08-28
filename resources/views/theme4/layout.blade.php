<?php
use App\Models\Admins\Pages;
use App\Models\Admins\Setting;
use App\Helpers\Cart;

$Site = $setting ?? Setting::where(['id' => '1'])->first();
$homeLayout = storefront_home_layout($Site);
$bodyClass = in_array($homeLayout, [2, 3], true) ? 'body-two' : '';
$primary = theme_color($Site, 'primary', 4);
$navColor = theme_color($Site, 'navigation', 4);
if ($navColor === '#111111' || $navColor === '#111') {
    $navColor = 'rgba(174, 28, 154, 0.08)';
}
$btnColor = theme_color($Site, 'button', 4);
$assets = $assets_url ?? asset('theme4') . '/';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(isset($meta_file) && $meta_file)
        @include($meta_file)
    @else
        @include('meta.default')
    @endif
    <link rel="icon" href="{{ storefront_img($Site->logo1 ?? '', $assets . 'assets/images/homepage-one/icon.png') }}">
    <link rel="stylesheet" href="{{ $assets }}css/swiper10-bundle.min.css">
    <link rel="stylesheet" href="{{ $assets }}css/bootstrap-5.3.2.min.css">
    <link rel="stylesheet" href="{{ $assets }}css/nouislider.min.css">
    <link rel="stylesheet" href="{{ $assets }}css/aos-3.0.0.css">
    <link rel="stylesheet" href="{{ $assets }}css/style.css">
    <link rel="stylesheet" href="{{ $assets }}css/theme-overrides.css">
    <style>
        :root {
            --shopus-accent: {{ $primary }};
            --shopus-nav: {{ $navColor }};
            --shopus-btn: {{ $btnColor }};
        }
    </style>
    {!! $Site->head_scripts ?? '' !!}
    @include('theme3.partials.tracking-pixels')
</head>
<body class="{{ $bodyClass }}">
    @include('theme4.header')
    @yield('content')
    @include('theme4.footer')

    <script src="{{ $assets }}assets/js/jquery_3.7.1.min.js"></script>
    <script src="{{ $assets }}assets/js/bootstrap_5.3.2.bundle.min.js"></script>
    <script src="{{ $assets }}assets/js/nouislider.min.js"></script>
    <script src="{{ $assets }}assets/js/aos-3.0.0.js"></script>
    <script src="{{ $assets }}assets/js/swiper10-bundle.min.js"></script>
    <script src="{{ $assets }}assets/js/shopus.js"></script>
    <script>
        window.addToCart = function (productId, qty, buyNow) {
            qty = qty || 1;
            fetch('{{ url('/cart/add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id: productId, qty: qty })
            }).then(function (r) { return r.json(); }).then(function (data) {
                if (data.qty !== undefined) {
                    var el = document.querySelectorAll('.t4-cart-count');
                    el.forEach(function (n) { n.textContent = data.qty; });
                }
                if (buyNow) {
                    window.location.href = '{{ url('/checkout') }}';
                    return;
                }
                if (data.msg) { alert(data.msg); }
            }).catch(function () { alert('Could not add to cart'); });
        };
    </script>
</body>
</html>
