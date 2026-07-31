<style>
    td{
        vertical-align: middle !important;
    }
    td img{
        width: 50px;
    }
</style>
@extends('admins.master')

@section('title','Products')

@section('product_active','active')

@section('product_active_c1','collapse in')

@section('product_child_2_active','active')


<?php
use App\Models\Admins\Category;
use App\Models\Admins\SubCategory;
use App\Models\Childcatagorie;
use App\Models\product;
use App\Models\Admins\Gallerie;
  ?>

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
<div class="row">
        <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Product List</h5>
            </div>
            <div class="ibox-content">
  
                <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example" >
            <thead>
            <tr>
                <th>Image</th>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td><img src="{{asset($product->image_one)}}" alt=""></td>
                    <td>{{$product->product_code}}</td>
                    
                    
                    
                    <td>{{$product->product_name}}</td>
                    <?php 
                        $category_id = Category::where(['id'=>$product->category_id])->get();
                    ?>
                    
                    <td>{{ (isset($category_id[0]->name)?$category_id[0]->name:''); }}</td>
            
                    
                    <td>{{$product->product_quantity}}</td>
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
                    <td>
                        <a href="{{route('admins.product_form',$product->id)}}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i>&nbsp;Edit</a>
                        <a data-href="{{route('admins.product_delete',['id'=>$product->id])}}" class="btn btn-danger btn-sm delete_record" href="javascript:void(0)"><i class="fa fa-times"></i>&nbsp;Delete</a>
                    </td>
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
                    type : "GET",
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