@extends('superadmin.layout')
@section('title', 'Stores')
@section('page_title', 'Stores')
@section('page_subtitle', 'Each domain gets its own theme and store panel')
@section('nav_stores', 'is-active')
@section('page_actions')
    <a class="sa-btn sa-btn-primary" href="{{ route('superadmin.stores.create') }}">Add store</a>
@endsection

@section('content')
<div class="sa-card">
    <table class="sa-table">
        <thead>
            <tr>
                <th>Store</th>
                <th>Primary domain</th>
                <th>Theme</th>
                <th>Meta</th>
                <th>TikTok</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($stores as $store)
            @php $primary = $store->domains->firstWhere('is_primary', 1) ?: $store->domains->first(); @endphp
            <tr>
                <td>
                    <strong>{{ $store->name }}</strong><br>
                    <small>{{ $store->email ?: $store->slug }}</small>
                </td>
                <td>{{ $primary->domain ?? '—' }}</td>
                <td>Theme {{ $store->active_theme }}</td>
                <td>{{ $store->meta_enabled ? 'On' : 'Off' }}</td>
                <td>{{ $store->tiktok_enabled ? 'On' : 'Off' }}</td>
                <td><span class="sa-badge {{ $store->status !== 'active' ? 'is-'.$store->status : '' }}">{{ $store->status }}</span></td>
                <td style="white-space:nowrap;">
                    <a class="sa-btn sa-btn-secondary" href="{{ route('superadmin.stores.edit', $store->id) }}">Edit</a>
                    <form action="{{ route('superadmin.stores.delete', $store->id) }}" method="post" style="display:inline;" onsubmit="return confirm('Delete this store?');">
                        @csrf
                        <button class="sa-btn sa-btn-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
