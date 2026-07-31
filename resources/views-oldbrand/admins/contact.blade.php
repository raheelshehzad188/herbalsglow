
<style>
    td{
        vertical-align: middle !important;
    }
    td img{
        width: 50px;
    }
</style>
@extends('admins.master')

@section('title','Messages')
@section('message','active')
<?php
use App\Models\Admins\Product; 
?>

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
<div class="row">
        <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Message List</h5>
            </div>
            <div class="ibox-content">
  
                <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example" >
            <thead>
            <tr>
                <!--<th>Image</th>-->
                <!--<th>Product Name</th>-->
                <th>Name</th>
                <th>Email</th>
                <!--<th>Quantity</th>-->
                <th>Message</th>
                <!--<th>City</th>-->
                <!--<th>Country</th>-->
                <!--<th>Address</th>-->
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach($orders as $product)
                <tr>
                    
                   
                    <td>{{$product->name}}</td>
                   
                    <td>{{$product->email}}</td>
                    <td>{{$product->meg}}</td>
                 
                    <td>
                    <a data-href="{{route('admins.meg_delete',['id'=>$product->id])}}" class="btn btn-danger btn-sm delete_record" href="javascript:void(0)"><i class="fa fa-times"></i></a></td>
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