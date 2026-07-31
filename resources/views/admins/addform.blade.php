@extends('admins.master')

@section('title','Orders')
@section('order','active')
@section('orderc1','collapse in')
@section('order5','active')


@section('content')
<div class="container-fluid">

    <!-- Page Heading -->


    <div class="row">
        <div class="col-md-12 mt-5">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session()->has('message'))
                <div class="alert alert-success" style="text-align: center;">
                    <b>{{ session()->get('message') }}</b>
                </div>
            @endif
            @if(session()->has('message1'))
                <div class="alert alert-danger" style="text-align: center;">
                    <b>{{ session()->get('message1') }}</b>
                </div>
            @endif
            <h1 class="h3 mb-4 text-white-800">Place Order</h1>
                <?php
                if(session()->get('customeorder')){
                ?>

                    <a href="{{url('admin/clear-order-form')}}" class="btn btn-danger float-right mb-2">Clear Order
                        Form</a>

                <?php }?>
            <form action="{{url('admin/add-form')}}" method="post">
                {{csrf_field()}}
                <div class="form-group">
                    <label for="product">Write Product Name</label>
                    <input type="text" name="product_name" class="form-control" value="<?php if(session()->get
                    ('customeorder')){$data =session()->get('customeorder'); echo $data['product_name']; }?>"
                           placeholder="Product Name" required>
                </div>
                <div class="form-group">
                    <label for="product">Product Price</label>
                    <input type="text" name="price" class="form-control" value="<?php if(session()->get('customeorder')){$data =session()->get('customeorder'); echo $data['price']; }?>"
                           placeholder="Product Price" required>
                </div>
                <div class="form-group">
                    <label for="name">Customer Name</label>
                    <input type="text" class="form-control" placeholder="Customer Name" value="<?php if(session()->get('customeorder')){$data =session()->get('customeorder'); echo $data['customer_name']; }?>" name="customer_name">
                </div>
                <div class="form-group">
                    <label for="qnt">Product Quantity</label>
                    <input type="number" min="1" class="form-control" value="<?php if(session()->get('customeorder'))
                    {$data =session()->get('customeorder'); echo $data['quantity']; }?>" id="qnt" name="quantity">
                </div>
                <div class="form-group">
                    <label for="name">Customer Mobile Number (<small class="text-danger">space not
                            allowed</small>)</label>
                    <input type="text" class="form-control" placeholder="Customer Mobile" value="<?php if(session()->get('customeorder')){$data =session()->get('customeorder'); echo $data['mobile_number']; }?> " name="mobile_number">
                </div>
                <div class="form-group">
                    <label for="add">Customer Address</label>
                    <textarea name="address" id="add" cols="30" rows="10" class="form-control"><?php if(session()
                            ->get('customeorder')){$data =session()->get('customeorder'); echo $data['address'];
                        }?></textarea>
                </div>

                <div class="col-md-6 mb-2" style="margin: 0 auto; text-align: center;">
                    <input type="submit" class="btn btn-info" value="Submit Order">
                </div>

            </form>
        </div>
    </div>

</div>
@endsection