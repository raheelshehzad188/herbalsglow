@extends('layout.app')
<?php
use App\Models\Catagorie;
use App\Models\Subcatagorie;
use App\Models\Childcatagorie;
use App\Models\Admins\Product;
use App\Models\Gallerie;
use Illuminate\Support\Facades\Session;
use App\Models\Admins\Setting;
use App\Models\Admins\Rating;
use App\Models\Admins\Slider;
  ?>
  @section('content')
  
  <!-- Bread Crumb STRAT -->
  <div class="banner inner-banner1 ">
    <div class="container">
      <section class="banner-detail center-xs">
        <h1 class="banner-title">Account</h1>
        <div class="bread-crumb right-side float-none-xs">
          <ul>
            <li><a href="/">Home</a>/</li>
            <li><span>Account</span></li>
          </ul>
        </div>
      </section>
    </div>
  </div>
  <!-- Bread Crumb END -->

  <!-- CONTAIN START -->
  <section class="checkout-section ptb-95">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-4">
          <div class="account-sidebar account-tab mb-xs-30">
            <div class="dark-bg tab-title-bg">
              <div class="heading-part">
                <div class="sub-title"><span></span> My Account</div>
              </div>
            </div>
            <div class="account-tab-inner">
              <ul class="account-tab-stap">
                <li id="step1" class="active"> <a href="javascript:void(0)">My Dashboard<i class="fa fa-angle-right"></i> </a> </li>
                <li id="step3"> <a href="javascript:void(0)">My Order List<i class="fa fa-angle-right"></i> </a> </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-9 col-sm-8">
          <div id="data-step1" class="account-content" data-temp="tabdata">
            <div class="row">
              <div class="col-xs-12">
                <div class="heading-part heading-bg mb-30">
                  <h2 class="heading m-0">Account Dashboard</h2>
                </div>
              </div>
            </div>
            @foreach($user as $v)
            <form action="/user_update" method="post" class="main-form full">
            @csrf
                <input type="hidden" value="{{$v->id}}" name="id">
              <div class="row">
                <div class="col-xs-12">
                  <div class="input-box">
                    <label for="old-pass">Name</label>
                    <input type="text" placeholder="Name" name="name" value="{{$v->name}}" required id="old-pass">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="input-box">
                    <label for="login-pass">Email</label>
                    <input type="email" placeholder="Enter your Email" name="email" value="{{$v->email}}" required id="login-pass">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="input-box">
                    <label for="re-enter-pass">Password</label>
                    <input type="text" placeholder=" your Password" name="pass" value="{{$v->password}}" required id="re-enter-pass">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="input-box">
                    <label for="re-enter-pass">Phone</label>
                    <input type="text" placeholder="Phone"  name="phone" value="{{$v->phone}}"required id="re-enter-pass">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="input-box">
                    <label for="re-enter-pass">City</label>
                    <input type="text" placeholder="City" name="city" value="{{$v->city}}" required id="re-enter-pass">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="input-box">
                    <label for="re-enter-pass">Country</label>
                    <input type="text" placeholder="Country" name="country" value="{{$v->country}}" required id="re-enter-pass">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="input-box">
                    <label for="re-enter-pass">Address</label>
                    <input type="text" placeholder="Address" name="address" value="{{$v->address}}" required id="re-enter-pass">
                  </div>
                </div>
                <div class="col-xs-12">
                  <button class="btn-color" type="submit" name="submit">Update</button>
                </div>
              </div>
            </form>
            @endforeach
          </div>
          <div id="data-step3" class="account-content" data-temp="tabdata" style="display:none">
            <div class="row">
              <div class="col-xs-12">
                <div class="heading-part heading-bg mb-30">
                  <h2 class="heading m-0">My Orders</h2>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 mb-xs-30">
                <div class="cart-item-table commun-table">
                  <div class="table-responsive">
                    <table class="table">
                       
                      <thead>
                        <tr>
                            <th>Order placed.</th>
                            <th>Order No.</th>
                            <th>Shipping Company.</th>
                            <th>Track_no.</th>
                            <th>Track_url.</th>
                            <th>Delivery Status.</th>
                            <th >Total.</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                           @foreach($order as $v)
                        <tr>
                        <td>{{$v->created_at}}</td>
                          <td>{{$v->order_no}}</td>
                          <td>{{$v->shipping_company}}</td>
                          <td>{{$v->track_no}}</td>
                          <td><a href="{{$v->track_url}}">{{$v->track_url}}</a></td>
                          <td>
                               @if($v->dstatus == 0)
                                    <p class="text-danger">Panding</p>
                                    @elseif($v->dstatus == 1)
                                    <p class="text-warning">Completed</p>
                                    @else
                                    <p class="text-success">Deliverd</p>
                                    @endif
                          </td>
                          <td>Rs:{{$v->amount}}</td>
                          
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
      </div>
    </div>
  </section>
  <!-- CONTAINER END --> 
  
   @endsection