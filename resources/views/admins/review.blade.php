@extends('admins.master')
@section('title','Reviews')
@section('page_title','Reviews')
@section('page_subtitle','Approve customer reviews before they appear on the store')
@section('review','is-active')

@section('content')
@php
    use App\Models\Admins\Product;
    $productNames = Product::withoutStore()->whereIn('id', $reviews->pluck('pid')->filter())->pluck('product_name', 'id');
@endphp
<div class="sa-card">
    <p class="sa-muted">Pending: <strong>{{ $pendingreviews->count() }}</strong> · Total: <strong>{{ $reviews->count() }}</strong>. Approved reviews show on the product page.</p>
    <table class="sa-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Customer</th>
                <th>Review</th>
                <th>Stars</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td>{{ $productNames[$review->pid] ?? ('#' . $review->pid) }}</td>
                <td>
                    {{ $review->name }}
                    <div class="sa-muted">{{ $review->email }}</div>
                </td>
                <td style="max-width:320px;white-space:normal;">{{ $review->review }}</td>
                <td>{{ (int) $review->rate }}/5</td>
                <td>
                    <label class="sa-check" style="margin:0;">
                        <input type="checkbox" class="js-review-status" data-id="{{ $review->id }}" {{ (int) $review->status === 1 ? 'checked' : '' }}>
                        <span>{{ (int) $review->status === 1 ? 'Approved' : 'Pending' }}</span>
                    </label>
                </td>
                <td>
                    <a class="sa-btn sa-btn-danger delete_records" href="javascript:void(0)" data-href="{{ route('admins.review_delete', ['id' => $review->id]) }}">Delete</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6">No reviews yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
$('.delete_records').on('click', function () {
    var href = $(this).data('href');
    swal({ title: 'Delete this review?', icon: 'warning', buttons: true, dangerMode: true })
        .then(function (ok) { if (ok) window.location.href = href; });
});
$('.js-review-status').on('change', function () {
    var $el = $(this);
    var status = $el.prop('checked') ? 1 : 0;
    $el.closest('label').find('span').text(status ? 'Approved' : 'Pending');
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: '{{ route('admins.update_review_status') }}',
        type: 'POST',
        data: { review_id: $el.data('id'), Status: status },
        success: function (response) {
            if (typeof showToastr === 'function') showToastr(response.msg, response.msg_type);
        }
    });
});
</script>
@endpush
