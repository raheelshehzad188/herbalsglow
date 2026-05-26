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
        <h1 class="banner-title">Track Order</h1>
        <div class="bread-crumb right-side float-none-xs">
          <ul>
            <li><a href="/">Home</a>/</li>
            <li><span>Track Order</span></li>
          </ul>
        </div>
      </section>
    </div>
  </div>
  
        @if($count > 0)
         <section class="checkout-section ptb-95">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="row">
                            <h2 class="account__content--title h3 mb-20">Orders History</h2>
                            <div class="account__table--area">
                                <table class="table">
                                    <thead class="account__table--header">
                                        <tr class="account__table--header__child">
                                            <th class="account__table--header__child--items">Order</th>
                                            <th class="account__table--header__child--items">Date</th>
                                            <!--<th class="account__table--header__child--items">Payment Status</th>-->
                                            <th class="account__table--header__child--items">Delivery Status</th>
                                            <th class="account__table--header__child--items">Total</th>	 	 	 	
                                        </tr>
                                    </thead>
                                    <tbody class="account__table--body mobile__none">
                                        @foreach($product as $v)
                                        <tr class="account__table--body__child">
                                            <td class="account__table--body__child--items">{{$v->order_no}}</td>
                                            <td class="account__table--body__child--items">{{$v->created_at}}</td>
                                            @if($v->dstatus == 0)
                                            <td class="account__table--body__child--items">Panding</td>
                                            @elseif($v->dstatus == 1)
                                            <td class="account__table--body__child--items">Completed</td>
                                            @else
                                            <td class="account__table--body__child--items">Deliverd</td>
                                            @endif
                                            <td class="account__table--body__child--items">Rs:{{$v->amount}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tbody class="account__table--body mobile__block">
                                        @foreach($product as $v)
                                        <tr class="account__table--body__child">
                                            <td class="account__table--body__child--items">{{$v->order_no}}</td>
                                            <td class="account__table--body__child--items">{{$v->created_at}}</td>
                                            @if($v->dstatus == 0)
                                            <td class="account__table--body__child--items">Panding</td>
                                            @elseif($v->dstatus == 1)
                                            <td class="account__table--body__child--items">Completed</td>
                                            @else
                                            <td class="account__table--body__child--items">Deliverd</td>
                                            @endif
                                            <td class="account__table--body__child--items">Rs:{{$v->amount}}</td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                </table>
                            </div>
                       </div>
                    </div>
                  </div>
                </div> 
        @else
            <section class="checkout-section ptb-95">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-8 col-lg-offset-3 col-sm-offset-2">
              <form class="main-form full" action="/trackorder" method="POST">
                  @csrf
                <div class="row">
                  <div class="col-xs-12 mb-20">
                    <div class="heading-part heading-bg">
                      <h2 class="heading">Track Order</h2>
                    </div>
                  </div>
                  <div class="col-xs-12">
                    <div class="input-box">
                      <label for="login-email">Track Order #:</label>
                      <input id="login-email" type="text" required name="num" placeholder="Enter Order No">
                    </div>
                  </div>
                 
                    <button name="submit" type="submit" class="btn-color right-side">Track Order</button>
                  </div>
                 
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
            
            @endif
            
  @endsection