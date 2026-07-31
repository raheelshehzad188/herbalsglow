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
  <div class="banner inner-banner1 ">
    <div class="container">
      <section class="banner-detail center-xs">
        <h1 class="banner-title">Checkout</h1>
        <div class="bread-crumb right-side float-none-xs">
          <ul>
            <li><a href="/">Home</a>/</li>
            <li><span>Checkout</span></li>
          </ul>
        </div>
      </section>
    </div>
  </div>
  
  <section class="checkout-section ptb-95">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-8 col-lg-offset-3 col-sm-offset-2">
              <form class="main-form full" action="/user_login" method="POST">
                  @csrf
                <div class="row">
                  <div class="col-xs-12 mb-20">
                    <div class="heading-part heading-bg">
                      <h2 class="heading">Checkout Login</h2>
                    </div>
                  </div>
                  <div class="col-xs-12">
                    <div class="input-box">
                      <label for="login-email">Email address</label>
                      <input id="login-email" type="email" required name="email" placeholder="Email Address">
                    </div>
                  </div>
                  <div class="col-xs-12">
                    <div class="input-box">
                      <label for="login-pass">Password</label>
                      <input id="login-pass" type="password" required name="pass" placeholder="Enter your Password">
                    </div>
                  </div>
                  <div class="col-xs-12">
                    <div class="check-box left-side"> 
                      <span>
                        <!--<input type="checkbox" name="remember_me" id="remember_me" class="checkbox">-->
                        <!--<label for="remember_me">Remember Me</label>-->
                      </span>
                    </div>
                    <button name="submit" type="submit" class="btn-color right-side">Log In</button>
                    <a href="/guest_checkout" class="btn btn-danger btn-color ">Guest Checkout</a>
                  </div>
                    <hr>
                  </div>
                  <div class="col-xs-12">
                    <div class="new-account align-center mt-20"> <span>New to DealShop?</span> <a class="link" title="Register with DealShop" href="/user_register">Create New Account</a> </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  @endsection