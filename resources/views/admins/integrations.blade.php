@extends('admins.master')
@section('title','Apps & Integrations')
@section('integrations_active','active')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="sa-page-head">
        <div>
            <h2 class="sa-h2">Apps</h2>
            <p class="sa-muted">Connect Meta Catalog / CAPI and TikTok Catalog / Events for this store.</p>
        </div>
    </div>

    @if(!$store)
        <div class="sa-card"><p>No store linked. Super Admin pehle store assign kare.</p></div>
    @else
        <p class="sa-muted" style="margin-bottom:14px;">Store: <strong>{{ $store->name }}</strong></p>
        <form method="post" action="{{ url('/admin/integrations') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="sa-card">
                        <h3>Meta / Facebook</h3>
                        @if(!$store->meta_enabled)
                            <p class="text-danger">Super Admin ne Meta disable rakha hai.</p>
                        @else
                            <label class="sa-check"><input type="checkbox" name="meta_enabled" value="1" @if($meta->is_enabled ?? false) checked @endif> Connected</label>
                            <label class="sa-check"><input type="checkbox" name="meta_catalog" value="1" @if($meta->catalog_enabled ?? false) checked @endif> Catalog sync</label>
                            <label class="sa-check"><input type="checkbox" name="meta_events" value="1" @if($meta->events_enabled ?? false) checked @endif> Events / Pixel (CAPI)</label>
                            <div class="sa-field"><label>Access token</label><input class="form-control" type="text" name="meta_access_token" placeholder="Leave blank to keep"></div>
                            <div class="sa-field"><label>Catalog ID</label><input class="form-control" type="text" name="meta_catalog_id" value="{{ $meta->catalog_id ?? '' }}"></div>
                            <div class="sa-field"><label>Pixel ID</label><input class="form-control" type="text" name="meta_pixel_id" value="{{ $meta->pixel_id ?? '' }}"></div>
                            <div class="sa-field"><label>Ad account ID</label><input class="form-control" type="text" name="meta_ad_account_id" value="{{ $meta->ad_account_id ?? '' }}"></div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="sa-card">
                        <h3>TikTok</h3>
                        @if(!$store->tiktok_enabled)
                            <p class="text-danger">Super Admin ne TikTok disable rakha hai.</p>
                        @else
                            <label class="sa-check"><input type="checkbox" name="tiktok_enabled" value="1" @if($tiktok->is_enabled ?? false) checked @endif> Connected</label>
                            <label class="sa-check"><input type="checkbox" name="tiktok_catalog" value="1" @if($tiktok->catalog_enabled ?? false) checked @endif> Catalog sync</label>
                            <label class="sa-check"><input type="checkbox" name="tiktok_events" value="1" @if($tiktok->events_enabled ?? false) checked @endif> Events API</label>
                            <div class="sa-field"><label>Access token</label><input class="form-control" type="text" name="tiktok_access_token" placeholder="Leave blank to keep"></div>
                            <div class="sa-field"><label>Catalog ID</label><input class="form-control" type="text" name="tiktok_catalog_id" value="{{ $tiktok->catalog_id ?? '' }}"></div>
                            <div class="sa-field"><label>Pixel ID</label><input class="form-control" type="text" name="tiktok_pixel_id" value="{{ $tiktok->pixel_id ?? '' }}"></div>
                            <div class="sa-field"><label>Business / Ad account ID</label><input class="form-control" type="text" name="tiktok_ad_account_id" value="{{ $tiktok->ad_account_id ?? '' }}"></div>
                        @endif
                    </div>
                </div>
            </div>
            <button class="sa-btn sa-btn-primary" type="submit">Save integrations</button>
        </form>

        <div class="row" style="margin-top:18px;">
            <div class="col-md-6">
                <div class="sa-card">
                    <h3>Meta actions</h3>
                    <form method="post" action="{{ url('/admin/integrations/meta/sync-catalog') }}" style="display:inline-block;margin-right:8px;">@csrf<button class="sa-btn sa-btn-secondary" type="submit">Sync catalog now</button></form>
                    <form method="post" action="{{ url('/admin/integrations/meta/test-event') }}" style="display:inline-block;">@csrf<button class="sa-btn sa-btn-secondary" type="submit">Send test PageView</button></form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="sa-card">
                    <h3>TikTok actions</h3>
                    <form method="post" action="{{ url('/admin/integrations/tiktok/sync-catalog') }}" style="display:inline-block;margin-right:8px;">@csrf<button class="sa-btn sa-btn-secondary" type="submit">Sync catalog now</button></form>
                    <form method="post" action="{{ url('/admin/integrations/tiktok/test-event') }}" style="display:inline-block;">@csrf<button class="sa-btn sa-btn-secondary" type="submit">Send test Pageview</button></form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
