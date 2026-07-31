
<style>
    td{
        vertical-align: middle !important;
    }
    td img{
        width: 50px;
    }
</style>
@extends('admins.master')

@section('title','Orders')
@section('order','active')
@section('orderc1','collapse in')
@section('corder','active')

<?php
use App\Models\Admins\Product; 
?>

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
<div class="row">
        <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Complete Orders List</h5>
            </div>
            <div class="ibox-content">
                <form method="POST" action="{{route('admins.delete_order')}}">
                <button type="submit" class="btn btn-danger btn-sm "><i class="fa fa-times"></i>Delete Selected</button>
  
                <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example" >
            <thead>
            <tr>
                <th><input type="checkbox" id="select_all"></th>
                <th>Sr.No</th>
                <!--<th>Product Name</th>-->
                <th>Customer Name</th>
                <!--<th>Customer Phone</th>-->
                <th>Status</th>
                <th>Total Price</th>
                <!--<th>City</th>-->
                <!--<th>Country</th>-->
                <!--<th>Address</th>-->
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp 
                @foreach($orders as $product)
                <tr>
                        @csrf
                    <td><input type="checkbox" class="emp_checkbox" value="{{$product->id}}" name="id[]"></td>
                   <td>{{$no++}}</td>
                    <td>{{$product->customer_name}}</td>
                    @if($product->dstatus == 0)
                    <td>Panding</td>
                    @elseif($product->dstatus == 1)
                    <td>Completed</td>
                    @elseif($product->dstatus == 2)
                    <td>Deliverd</td>
                    @elseif($product->dstatus == 3)
                    <td>Canceled</td>
                    @elseif($product->dstatus == 4)
                   <td>Dispatched</td>
                   @endif
                    <td>{{$product->amount}}</td>
                 
                    <td><a href="{{route('admins.edit_order',['id'=>$product->id])}}" class="btn btn-primary btn-sm " ><i class="fa fa-edit"></i></a>
                    <a data-href="{{route('admins.order_delete',['id'=>$product->id])}}" class="btn btn-danger btn-sm delete_record" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                    </td>
                    
                    </form>
                </tr>
                @endforeach
            </tbody>
            </table>
                </div>
  
            </div>
        </div>
    </div>
    </div>
  </div>
@endsection


@push('scripts')
  <script>
      $(document).on('click', '#select_all', function() {          	
		$(".emp_checkbox").prop("checked", this.checked);
		$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
	});	
	$(document).on('click', '.emp_checkbox', function() {		
		if ($('.emp_checkbox:checked').length == $('.emp_checkbox').length) {
			$('#select_all').prop('checked', true);
		} else {
			$('#select_all').prop('checked', false);
		}
		$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
	}); 
       function updateStatus(status,product_id)
       {
            if(product_id>0){
                $.ajax({
                    headers: {
                            'X-CSRF-TOKEN': "{{csrf_token()}}",
                        },
                    url : "{{route('admins.update_product_status')}}",
                    type : "POST",
                    data : {
                        product_id : product_id,
                        Status : status,
                    },
                    success : function(response){
                        showToastr(response.msg,response.msg_type);
                    }
                });
            }
       }
      $(document).ready(function(){
          let status=0;
          let product_id=0;
          $('input[name="product_status"]').change(function(){
            if($(this).is(':checked')){
                status=1;
                product_id=$(this).data('id');
            }else{
                status=0;
                product_id=$(this).data('id');
            }
            updateStatus(status,product_id);
          });
      });
  </script>
@endpush