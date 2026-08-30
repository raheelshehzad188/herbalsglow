@extends('admins.master')
@section('title','Flush data')
@section('page_title','Flush data')
@section('page_subtitle','Permanently delete catalog data for this store')
@section('flush_data','is-active')

@section('content')
<div class="imp-wrap">
    <div class="sa-card">
        <h2>This store only</h2>
        <p class="sa-muted">Flush removes catalog records from the current ShopUS store. Orders, settings, pages, and other stores are not touched.</p>
        <div class="sa-grid sa-grid-4" style="margin-top:16px;">
            <div class="sa-stat sa-card"><div class="label">Products</div><div class="value">{{ $counts['products'] }}</div></div>
            <div class="sa-stat sa-card"><div class="label">Brands</div><div class="value">{{ $counts['brands'] }}</div></div>
            <div class="sa-stat sa-card"><div class="label">Categories</div><div class="value">{{ $counts['categories'] }}</div></div>
            <div class="sa-stat sa-card"><div class="label">Subcategories</div><div class="value">{{ $counts['subcategories'] }}</div></div>
        </div>
    </div>

    <div class="sa-card">
        <h3>Flush catalog</h3>
        <form method="post" action="{{ url('/admin/flush-data') }}" id="flushForm">
            @csrf
            <div class="sa-field">
                <label for="scope">What to delete</label>
                <select name="scope" id="scope" required>
                    <option value="all">All data (products, brands, categories)</option>
                    <option value="products">Just products</option>
                    <option value="brands">Just brands</option>
                    <option value="categories">Just categories</option>
                </select>
                <small>Just brands / categories also clears those links on remaining products.</small>
            </div>
            <label class="sa-check" style="margin:12px 0 18px;display:flex;gap:8px;align-items:flex-start;">
                <input type="checkbox" name="confirm" value="1" required>
                <span>I understand this cannot be undone.</span>
            </label>
            <button class="sa-btn sa-btn-danger" type="submit" id="flushBtn">Flush data</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#flushForm').on('submit', function (e) {
    var label = $('#scope option:selected').text();
    if (!confirm('Permanently flush “' + label + '” for this store? This cannot be undone.')) {
        e.preventDefault();
        return false;
    }
    $('#flushBtn').prop('disabled', true).text('Flushing…');
});
</script>
@endpush
