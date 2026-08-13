@extends('admins.master')

@section('title','Products')
@section('product_active','active')
@section('product_active_c1','collapse in')
@section('product_child_2_active','active')

@php
use App\Models\Admins\Category;
@endphp

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="sa-page-head">
        <div>
            <h2 class="sa-h2">Products</h2>
            <p class="sa-muted">Manage catalog for this store only.</p>
        </div>
        <a href="{{ route('admins.product_form') }}" class="sa-btn sa-btn-primary">Add product</a>
    </div>

    <div class="sa-card" style="padding:0;overflow:hidden;">
        <div class="table-responsive">
            <table class="sa-table dataTables-example">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Inventory</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    @php
                        $category_id = Category::where(['id'=>$product->category_id])->get();
                        $catName = isset($category_id[0]->name) ? $category_id[0]->name : '—';
                    @endphp
                    <tr>
                        <td>
                            <div class="sa-product-cell">
                                <img src="{{ asset($product->image_one) }}" alt="">
                                <div>
                                    <strong>{{ $product->product_name }}</strong>
                                    <div class="sa-muted">{{ $product->product_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $catName }}</td>
                        <td>{{ $product->product_quantity }} in stock</td>
                        <td>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="product_status" data-id="{{$product->id}}" <?php echo $product->status==1 ? 'checked' : null; ?> class="onoffswitch-checkbox" id="example-{{$product->id}}">
                                    <label class="onoffswitch-label" for="example-{{$product->id}}">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </td>
                        <td style="white-space:nowrap;">
                            <a href="{{ route('admins.product_form',$product->id) }}" class="sa-btn sa-btn-secondary">Edit</a>
                            <a data-href="{{ route('admins.product_delete',['id'=>$product->id]) }}" class="sa-btn sa-btn-danger delete_record" href="javascript:void(0)">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateStatus(status,product_id) {
    $.ajax({
        url : "{{route('admins.update_product_status')}}",
        type: "POST",
        data: { _token: "{{ csrf_token() }}", status: status, product_id: product_id },
    });
}
$(document).on('change','input[name=product_status]', function(){
    updateStatus($(this).is(':checked') ? 1 : 0, $(this).data('id'));
});
</script>
@endpush
