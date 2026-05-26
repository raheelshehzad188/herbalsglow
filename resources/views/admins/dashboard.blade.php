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
                  <h5><a href="products">Products</a></h5>
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
      @if(count($urreviews))
      <div class="col-lg-3">
          <div class="ibox float-e-margins">
              <div class="ibox-title">
                  <h5><a href="review">New reviews</a></h5>
              </div>
              <div class="ibox-content">
                  <h1 class="no-margins">{{ count($urreviews) }}</h1>
                  <small>Total New reviews</small>
              </div>
          </div>
      </div>
      @endif
      @if(count($corders))
      <div class="col-lg-3">
          <div class="ibox float-e-margins">
              <div class="ibox-title">
                  <h5><a href="orders">Completed orders</a></h5>
              </div>
              <div class="ibox-content">
                  <h1 class="no-margins">{{ count($corders) }}</h1>
                  <small>Total Completed orders</small>
              </div>
          </div>
      </div>
      @endif
      @if(count($unrorders))
      <div class="col-lg-3">
          <div class="ibox float-e-margins">
              <div class="ibox-title">
                  <h5><a href="orders">New orders</a></h5>
              </div>
              <div class="ibox-content">
                  <h1 class="no-margins">{{ count($unrorders) }}</h1>
                  <small>Total New orders</small>
              </div>
          </div>
      </div>
      @endif
  </div>
</div>
    @endsection