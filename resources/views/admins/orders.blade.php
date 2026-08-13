@extends('admins.master')

@section('title','Orders')
@section('order','active')
@section('orderc1','collapse in')
@section('order1','active')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="sa-page-head">
        <div>
            <h2 class="sa-h2">Orders</h2>
            <p class="sa-muted">Orders for this store.</p>
        </div>
    </div>

    <div class="sa-card" style="padding:0;overflow:hidden;">
        <form method="POST" action="{{route('admins.delete_order')}}" style="padding:14px 14px 0;">
            @csrf
            <button type="submit" class="sa-btn sa-btn-danger"><i class="fa fa-times"></i> Delete selected</button>
            <div class="table-responsive" style="margin-top:12px;">
                <table class="sa-table dataTables-example">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all"></th>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $no = 1; @endphp
                    @foreach($orders as $product)
                        <tr>
                            <td><input type="checkbox" class="emp_checkbox" value="{{$product->id}}" name="id[]"></td>
                            <td>{{$no++}}</td>
                            <td><strong>{{$product->customer_name}}</strong></td>
                            <td>
                                @if($product->dstatus == 0)
                                    <span class="sa-badge is-draft">Pending</span>
                                @elseif($product->dstatus == 1)
                                    <span class="sa-badge">Completed</span>
                                @elseif($product->dstatus == 2)
                                    <span class="sa-badge">Delivered</span>
                                @elseif($product->dstatus == 3)
                                    <span class="sa-badge is-paused">Canceled</span>
                                @elseif($product->dstatus == 4)
                                    <span class="sa-badge">Dispatched</span>
                                @endif
                            </td>
                            <td>Rs {{$product->amount}}</td>
                            <td style="white-space:nowrap;">
                                <a href="{{ url('admin/order-detail/'.$product->id) }}" target="_blank" class="sa-btn sa-btn-secondary">View</a>
                                <a data-href="{{route('admins.order_delete',['id'=>$product->id])}}" class="sa-btn sa-btn-danger delete_record" href="javascript:void(0)">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).on('click', '#select_all', function() {
    $(".emp_checkbox").prop("checked", this.checked);
});
$(document).on('click', '.emp_checkbox', function() {
    $('#select_all').prop('checked', $('.emp_checkbox:checked').length == $('.emp_checkbox').length);
});
</script>
@endpush
