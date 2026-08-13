@extends('superadmin.layout')
@php
    $isEdit = !empty($store);
    $meta = $isEdit ? $store->integrations->firstWhere('provider', 'meta') : null;
    $tiktok = $isEdit ? $store->integrations->firstWhere('provider', 'tiktok') : null;
    $primary = $isEdit ? ($store->domains->firstWhere('is_primary', 1) ?: $store->domains->first()) : null;
@endphp
@section('title', $isEdit ? 'Edit store' : 'Add store')
@section('page_title', $isEdit ? 'Edit store' : 'Add store')
@section('page_subtitle', 'Domain · Theme · Meta · TikTok')
@section('nav_stores', 'is-active')
@section('page_actions')
    <a class="sa-btn sa-btn-secondary" href="{{ route('superadmin.stores') }}">Back</a>
@endsection

@section('content')
<form method="post" action="{{ $isEdit ? route('superadmin.stores.update', $store->id) : route('superadmin.stores.store') }}">
    @csrf
    <div class="sa-grid sa-grid-2">
        <div class="sa-card">
            <h3>Store details</h3>
            <div class="sa-field">
                <label>Store name</label>
                <input type="text" name="name" value="{{ old('name', $store->name ?? '') }}" required>
            </div>
            <div class="sa-field">
                <label>Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $store->slug ?? '') }}" placeholder="auto from name">
            </div>
            <div class="sa-field">
                <label>Store email</label>
                <input type="email" name="email" value="{{ old('email', $store->email ?? '') }}">
            </div>
            <div class="sa-field">
                <label>Primary domain</label>
                <input type="text" name="domain" value="{{ old('domain', $primary->domain ?? '') }}" placeholder="mystore.com" required>
                <small>Front theme resolve hoga is domain se</small>
            </div>
            <div class="sa-grid sa-grid-2">
                <div class="sa-field">
                    <label>Theme</label>
                    <select name="active_theme" required>
                        @foreach([1,2,3] as $t)
                            <option value="{{ $t }}" @if((int)old('active_theme', $store->active_theme ?? 3) === $t) selected @endif>Theme {{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sa-field">
                    <label>Status</label>
                    <select name="status" required>
                        @foreach(['active','paused','draft'] as $st)
                            <option value="{{ $st }}" @if(old('status', $store->status ?? 'active') === $st) selected @endif>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="sa-grid sa-grid-2">
                <div class="sa-field">
                    <label>Currency</label>
                    <input type="text" name="currency" value="{{ old('currency', $store->currency ?? 'PKR') }}">
                </div>
                <div class="sa-field">
                    <label>Timezone</label>
                    <input type="text" name="timezone" value="{{ old('timezone', $store->timezone ?? 'Asia/Karachi') }}">
                </div>
            </div>
        </div>

        <div class="sa-card">
            <h3>Store owner login</h3>
            <p style="color:#6d7175;font-size:13px;margin-top:0;">Shopify-style separate panel: <code>/admin</code></p>
            <div class="sa-field">
                <label>Owner name</label>
                <input type="text" name="owner_name" value="{{ old('owner_name') }}">
            </div>
            <div class="sa-field">
                <label>Owner email</label>
                <input type="email" name="owner_email" value="{{ old('owner_email') }}">
            </div>
            <div class="sa-field">
                <label>Owner password</label>
                <input type="password" name="owner_password" placeholder="{{ $isEdit ? 'Leave blank to keep' : 'Min 6 chars' }}">
            </div>
        </div>
    </div>

    <div class="sa-grid sa-grid-2">
        <div class="sa-card">
            <h3>Meta (Facebook) apps</h3>
            <label class="sa-check"><input type="checkbox" name="meta_enabled" value="1" @if(old('meta_enabled', $store->meta_enabled ?? false)) checked @endif> Enable Meta for this store</label>
            <label class="sa-check"><input type="checkbox" name="meta_catalog" value="1" @if(old('meta_catalog', $meta->catalog_enabled ?? false)) checked @endif> Catalog API</label>
            <label class="sa-check"><input type="checkbox" name="meta_events" value="1" @if(old('meta_events', $meta->events_enabled ?? false)) checked @endif> Events / Pixel API</label>
            <div class="sa-field"><label>Access token</label><input type="text" name="meta_access_token" value="{{ old('meta_access_token') }}" placeholder="EAAB..."></div>
            <div class="sa-field"><label>Catalog ID</label><input type="text" name="meta_catalog_id" value="{{ old('meta_catalog_id', $meta->catalog_id ?? '') }}"></div>
            <div class="sa-field"><label>Pixel ID</label><input type="text" name="meta_pixel_id" value="{{ old('meta_pixel_id', $meta->pixel_id ?? '') }}"></div>
            <div class="sa-field"><label>Ad account ID</label><input type="text" name="meta_ad_account_id" value="{{ old('meta_ad_account_id', $meta->ad_account_id ?? '') }}"></div>
        </div>

        <div class="sa-card">
            <h3>TikTok apps</h3>
            <label class="sa-check"><input type="checkbox" name="tiktok_enabled" value="1" @if(old('tiktok_enabled', $store->tiktok_enabled ?? false)) checked @endif> Enable TikTok for this store</label>
            <label class="sa-check"><input type="checkbox" name="tiktok_catalog" value="1" @if(old('tiktok_catalog', $tiktok->catalog_enabled ?? false)) checked @endif> Catalog API</label>
            <label class="sa-check"><input type="checkbox" name="tiktok_events" value="1" @if(old('tiktok_events', $tiktok->events_enabled ?? false)) checked @endif> Events API</label>
            <div class="sa-field"><label>Access token</label><input type="text" name="tiktok_access_token" value="{{ old('tiktok_access_token') }}"></div>
            <div class="sa-field"><label>Catalog ID</label><input type="text" name="tiktok_catalog_id" value="{{ old('tiktok_catalog_id', $tiktok->catalog_id ?? '') }}"></div>
            <div class="sa-field"><label>Pixel ID</label><input type="text" name="tiktok_pixel_id" value="{{ old('tiktok_pixel_id', $tiktok->pixel_id ?? '') }}"></div>
            <div class="sa-field"><label>Ad account ID</label><input type="text" name="tiktok_ad_account_id" value="{{ old('tiktok_ad_account_id', $tiktok->ad_account_id ?? '') }}"></div>
        </div>
    </div>

    <div style="display:flex;gap:10px;">
        <button class="sa-btn sa-btn-primary" type="submit">Save store</button>
        <a class="sa-btn sa-btn-secondary" href="{{ route('superadmin.stores') }}">Cancel</a>
    </div>
</form>
@endsection
