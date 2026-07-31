
<style>
    td{
        vertical-align: middle !important;
    }
    td img{
        width: 50px;
    }
</style>
@extends('admins.master')

@section('title','New Orders')


@section('all-orders','active')
<?php
use App\Models\Admins\Product; 
?>

 <?php $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
    
    ?>

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
<div class="row">
        <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Orders List</h5>
            </div>
            <div class="ibox-content">
              @if(isset($orders) && count($orders) > 0)
                <div class="table-responsive">
                    
                    
            <table class="table table-striped table-bordered table-hover dataTables-example" id="fetch-api" >
                <thead>
            <tr>
                <th>Sr.No</th>
              
                <th>Customer Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>City</th>
                <th>Address</th>
                <th>Status</th>
                 <th>Action</th>
                <!--<th>City</th>-->
                <!--<th>Country</th>-->
                <!--<th>Address</th>-->
                
            </tr>
            </thead>
            <?php
            /*
            ?>
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
            <?php
            */
            ?>
            <tbody>
                @php
                    $no = 1;
                @endphp 
                @foreach($orders as $product)
                <tr>
                        @csrf
                    <td><input type="checkbox" class="emp_checkbox" value="{{$product->id}}" name="id[]"></td>
                   <td>{{$no++}}</td>
                    <td>{{$product->product_name}}</td>
                    <td>{{$product->customer_name}}</td>
                    <td>{{$product->mobile_number}}</td>
                    <td>{{$product->quantity}}</td>
                    <td>{{$product->address}}</td>
                    <td>
                    <a href="<?php echo url('admin/order-detail/'.$product->id);?>" target="_blank" class="btn btn-info
                        btn-block">View More</a>
                            <a href="<?php echo url('admin/Order-delete/'.$product->id);?>" class="btn btn-danger
                        btn-block">Delete</a>
                        
                        </td>

                 
                    
                    </form>
                </tr>
                @endforeach
            </tbody>
            </table>
                </div>
                
                
                
                 @elseif(isset($Detail))
                 <div class="row">
                       <div class="col-md-12">
                <div class="row">
                   <div class="col-md-4 border">
            <p><b>Product Detail</b></p>
            @foreach ($products as $product)
                <p>Product Name: {{ $product['name'] }}</p>
                <p>Quantity: {{ $product['quantity'] }}</p>
            @endforeach
        </div>
                    <div class="col-md-4 border">
                        <p>
                            <b>Customer Name</b>
                        </p>
                        <p>
                            <?php echo $Detail->customer_name;?>
                        </p>
                    </div>
                    
                    <div class="col-md-4 border">
                        <p>
                            <b>Mobile Number</b>
                        </p>
                        <p>
                            <?php echo $Detail->phone;?>
                        </p>
                    </div>
                    <div class="col-md-4 border">
                        <p>
                            <b>Email</b>
                        </p>
                        <p>
                            <?php echo $Detail->email;?>
                        </p>
                    </div>
                    <div class="col-md-4 border">
                        <p>
                            <b>Customer address</b>
                        </p>
                        <p>
                            <?php echo $Detail->address;?>
                        </p>
                    </div>
                    
                     <div class="col-md-4 border">
                        <p>
                            <b>Price</b>
                        </p>
                        <p>
                           {{ $Detail->amount + $setting->shipping_charges }}
                           
                        </p>
                    </div>
                   
                    <div class="col-md-4 border">
                        <p>
                            <b>Country</b>
                        </p>
                        <p>
                            <?php echo $Detail->country;?>
                        </p>
                    </div>
                   
                    <div class="col-md-4 border">
                        <p>
                            <b>City</b>
                        </p>
                        <p>
                            <?php echo $Detail->city;?>
                        </p>
                    </div>
                    
                     <div class="col-md-4 border">
                        <p>
                            <b>Order No</b>
                        </p>
                        <p>
                            <?php echo $Detail->order_no;?>
                        </p>
                    </div>
                   
                    
                    
                   
                   
                </div>
            </div>
                 </div>
                 @endif
  
            </div>
        </div>
    </div>
    </div>
  </div>
@endsection


@push('scripts')
  <script>
  
  
   $(document).ready(function(){
            $('#fetch-api').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                 ajax: '{{ url("admin/all-orders-api"); }}?status=2',
              processing: true,
              serverSide: true,
              "bDestroy": true,
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });

        });
  
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