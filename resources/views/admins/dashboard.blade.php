@extends('admins.master')
@section('dashboard_active','is-active')
@section('title','Home')
@section('page_title','Home')
@section('page_subtitle','Today at a glance')
@section('page_actions')
    <a class="sa-btn sa-btn-primary" href="{{ route('admins.product_form') }}">Add product</a>
@endsection

@section('content')
<div class="sa-grid sa-grid-4">
    <a class="sa-card sa-stat sa-stat-link" href="{{ url('/admin/orders') }}">
        <div class="label">New orders</div>
        <div class="value">{{ count($unrorders) }}</div>
    </a>
    <a class="sa-card sa-stat sa-stat-link" href="{{ url('/admin/complete_orders') }}">
        <div class="label">Completed</div>
        <div class="value">{{ count($corders) }}</div>
    </a>
    <a class="sa-card sa-stat sa-stat-link" href="{{ route('admins.products') }}">
        <div class="label">Products</div>
        <div class="value">{{ count($products) }}</div>
    </a>
    <a class="sa-card sa-stat sa-stat-link" href="{{ route('admins.category') }}">
        <div class="label">Categories</div>
        <div class="value">{{ count($categories) }}</div>
    </a>
</div>

<div class="sa-grid sa-grid-2">
    <div class="sa-card">
        <h3>Reviews</h3>
        <p class="sa-muted">{{ count($rating) }} total · {{ count($urreviews) }} unread</p>
        <a class="sa-btn sa-btn-secondary" href="{{ url('/admin/review') }}">Open reviews</a>
    </div>
    <div class="sa-card">
        <h3>Get started</h3>
        <p class="sa-muted">Add products, pick a theme, then connect Meta or TikTok.</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a class="sa-btn sa-btn-primary" href="{{ route('admins.product_form') }}">Add product</a>
            <a class="sa-btn sa-btn-secondary" href="{{ route('admins.theme_settings') }}">Theme customizer</a>
            <a class="sa-btn sa-btn-secondary" href="{{ route('admins.setting') }}">Store settings</a>
            <a class="sa-btn sa-btn-secondary" href="{{ url('/admin/integrations') }}">Apps</a>
        </div>
    </div>
</div>
@endsection
