@extends('admins.master')
@section('title','Import errors')
@section('page_title','Failed import items')
@section('import_data','is-active')
@php
    $source = ($source ?? 'shopify') === 'woocommerce' ? 'woocommerce' : 'shopify';
    $backUrl = $source === 'woocommerce' ? url('/admin/import-data?source=woocommerce') : url('/admin/import-data');
    $retryUrl = $source === 'woocommerce' ? url('/admin/import-data/woocommerce/retry') : url('/admin/import-data/retry');
@endphp
@section('content')
<div class="sa-card">
    <h3>Import error log</h3>
    <p class="sa-muted">Tokens and secrets are never stored in these messages.</p>
    <table class="sa-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Remote ID</th>
                <th>Name</th>
                <th>Error</th>
                <th>When</th>
                <th>Retry</th>
            </tr>
        </thead>
        <tbody>
        @forelse($errors as $err)
            <tr>
                <td>{{ $err->resource_type }}</td>
                <td>{{ $err->shopify_id }}</td>
                <td>{{ $err->item_name }}</td>
                <td>{{ $err->message }}</td>
                <td>{{ $err->created_at }}</td>
                <td>{{ $err->retry_status }}</td>
            </tr>
        @empty
            <tr><td colspan="6">No failed items.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="imp-actions" style="margin-top:16px;">
        <a class="sa-btn sa-btn-secondary" href="{{ $backUrl }}">Back</a>
        @if($job)
        <form method="post" action="{{ $retryUrl }}" style="display:inline;">
            @csrf
            <button class="sa-btn sa-btn-primary" type="submit">Retry failed items</button>
        </form>
        @endif
    </div>
</div>
@endsection
