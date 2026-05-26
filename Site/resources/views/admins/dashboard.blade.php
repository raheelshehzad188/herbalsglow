@extends('admins.master')

@section('dashboard_active','active')

@section('title','Dashboard')

@section('content')
<div class="wrapper wrapper-content">
  <div class="row">
      <div class="col-lg-3">
          <div class="ibox float-e-margins">
              <div class="ibox-title">
                  <h5><a href="category">Categories</a></h5>
              </div>
              <div class="ibox-content">
                  <h1 class="no-margins">{{ count($categories) }}</h1>
                  <small>Total Categories</small>
              </div>
          </div>
      </div>
      <div class="col-lg-3">
          <div class="ibox float-e-margins">
              <div class="ibox-title">
                  <h5><a href="product_form">Products</a></h5>
              </div>
              <div class="ibox-content">
                  <h1 class="no-margins">{{ count($products) }}</h1>
                  <small>Total Products</small>
              </div>
          </div>
      </div>
      <div class="col-lg-3">
          <div class="ibox float-e-margins">
              <div class="ibox-title">
                  <h5><a href="review">Reviews</a></h5>
              </div>
              <div class="ibox-content">
                  <h1 class="no-margins">{{ count($rating) }}</h1>
                  <small>Total Reviews</small>
              </div>
          </div>
      </div>
  </div>
</div>
    @endsection