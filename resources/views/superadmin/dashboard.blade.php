@extends('superadmin.layout')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('nav_dashboard', 'is-active')
@section('page_actions')
    <a class="sa-btn sa-btn-primary" href="{{ route('superadmin.stores.create') }}">Add store</a>
@endsection

@section('content')
<div class="sa-grid sa-grid-4">
    <div class="sa-card sa-stat"><div class="label">Stores</div><div class="value">{{ $stats['stores'] }}</div></div>
    <div class="sa-card sa-stat"><div class="label">Domains</div><div class="value">{{ $stats['domains'] }}</div></div>
    <div class="sa-card sa-stat"><div class="label">Meta Apps</div><div class="value">{{ $stats['meta_enabled'] }}</div></div>
    <div class="sa-card sa-stat"><div class="label">TikTok Apps</div><div class="value">{{ $stats['tiktok_enabled'] }}</div></div>
</div>

<div class="sa-card">
    <h2>Recent stores</h2>
    <table class="sa-table">
        <thead>
            <tr>
                <th>Store</th>
                <th>Domain</th>
                <th>Theme</th>
                <th>Apps</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($stores as $store)
            <tr>
                <td><strong>{{ $store->name }}</strong><br><small>{{ $store->slug }}</small></td>
                <td>{{ optional($store->domains->firstWhere('is_primary', 1))->domain ?? optional($store->domains->first())->domain ?? '—' }}</td>
                <td>Theme {{ $store->active_theme }}</td>
                <td>
                    @if($store->meta_enabled) Meta @endif
                    @if($store->tiktok_enabled) TikTok @endif
                    @if(!$store->meta_enabled && !$store->tiktok_enabled)—@endif
                </td>
                <td><span class="sa-badge {{ $store->status !== 'active' ? 'is-'.$store->status : '' }}">{{ $store->status }}</span></td>
                <td><a class="sa-btn sa-btn-secondary" href="{{ route('superadmin.stores.edit', $store->id) }}">Edit</a></td>
            </tr>
        @empty
            <tr><td colspan="6">No stores yet. <a href="{{ route('superadmin.stores.create') }}">Create your first store</a>.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
