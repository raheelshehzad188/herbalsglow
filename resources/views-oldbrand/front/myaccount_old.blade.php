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
    <main class="main__content_wrapper">

        <!-- Start breadcrumb section -->
        <section class="breadcrumb__section breadcrumb__bg">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="breadcrumb__content text-center">
                            <h1 class="breadcrumb__content--title text-white mb-25">My Account</h1>
                            <ul class="breadcrumb__content--menu d-flex justify-content-center">
                                <li class="breadcrumb__content--menu__items"><a class="text-white" href="index.html">Home</a></li>
                                <li class="breadcrumb__content--menu__items"><span class="text-white">My Account</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End breadcrumb section -->
        
        <!-- my account section start -->
        <section class="my__account--section section--padding">
            <div class="container">
                 @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
                <!--<p class="account__welcome--text">Hello, Admin welcome to your dashboard!</p>-->
                <div class="my__account--section__inner border-radius-10 d-flex">
                    <div class="account__left--sidebar">
                        <h2 class="account__content--title h3 mb-20">My Profile</h2>
                        <ul class="account__menu">
                            <li class="account__menu--list active"><a href="my-account.html">Dashboard</a></li>
                            <!--<li class="account__menu--list"><a href="my-account-2.html">Addresses</a></li>-->
                            <!--<li class="account__menu--list"><a href="wishlist.html">Wishlist</a></li>-->
                            <li class="account__menu--list"><a href="/logout">Log Out</a></li>
                        </ul>
                    </div>
                    <div class="account__wrapper">
                        <div class="account__content">
                            <h3 class="account__content--title mb-20">User Information</h3>
                            <!--<button class="new__address--btn primary__btn mb-25" type="button">Add a new address</button>-->
                            <div class="account__details two">
                               <form action="/user_update" method="post">
                                   @csrf
                                   @foreach($user as $v)
                                   <input type="hidden" value="{{$v->id}}" name="id">
                                   <input class="account__login--input" placeholder="Name" name="name" value="{{$v->name}}" type="text" required>
                                   <input class="account__login--input" placeholder="Email" name="email" value="{{$v->email}}" type="text" required>
                                   <input class="account__login--input" placeholder="Name" name="pass" value="{{$v->password}}" type="text" required>
                                   <input class="account__login--input" placeholder="Name" name="phone" value="{{$v->phone}}" type="text" required>
                                   <input class="account__login--input" placeholder="Name" name="city" value="{{$v->city}}" type="text" required>
                                   <input class="account__login--input" placeholder="Name" name="country" value="{{$v->country}}" type="text" required>
                                   <input class="account__login--input" placeholder="Name" name="address" value="{{$v->address}}" type="text" required>
                                   @endforeach
                                   <button class="account__details--footer__btn" type="submit">Save</button>
                               </form>
                            
                                
                                
                            </div>
                        </div>
                    </div>
                    <div class="account__wrapper">
                        <div class="account__content">
                            <h2 class="account__content--title h3 mb-20">Orders History</h2>
                            <div class="account__table--area">
                                <table class="account__table">
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
                                        @foreach($order as $v)
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
                                        @foreach($order as $v)
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
        </section>
        <!-- my account section end -->

        <!-- Start shipping section -->
        <!--<section class="shipping__section2 shipping__style3 section--padding pt-0">-->
        <!--    <div class="container">-->
        <!--        <div class="shipping__section2--inner shipping__style3--inner d-flex justify-content-between">-->
        <!--            <div class="shipping__items2 d-flex align-items-center">-->
        <!--                <div class="shipping__items2--icon">-->
        <!--                    <img src="assets/img/other/shipping1.png" alt="">-->
        <!--                </div>-->
        <!--                <div class="shipping__items2--content">-->
        <!--                    <h2 class="shipping__items2--content__title h3">Shipping</h2>-->
        <!--                    <p class="shipping__items2--content__desc">From handpicked sellers</p>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="shipping__items2 d-flex align-items-center">-->
        <!--                <div class="shipping__items2--icon">-->
        <!--                    <img src="assets/img/other/shipping2.png" alt="">-->
        <!--                </div>-->
        <!--                <div class="shipping__items2--content">-->
        <!--                    <h2 class="shipping__items2--content__title h3">Payment</h2>-->
        <!--                    <p class="shipping__items2--content__desc">From handpicked sellers</p>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="shipping__items2 d-flex align-items-center">-->
        <!--                <div class="shipping__items2--icon">-->
        <!--                    <img src="assets/img/other/shipping3.png" alt="">-->
        <!--                </div>-->
        <!--                <div class="shipping__items2--content">-->
        <!--                    <h2 class="shipping__items2--content__title h3">Return</h2>-->
        <!--                    <p class="shipping__items2--content__desc">From handpicked sellers</p>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="shipping__items2 d-flex align-items-center">-->
        <!--                <div class="shipping__items2--icon">-->
        <!--                    <img src="assets/img/other/shipping4.png" alt="">-->
        <!--                </div>-->
        <!--                <div class="shipping__items2--content">-->
        <!--                    <h2 class="shipping__items2--content__title h3">Support</h2>-->
        <!--                    <p class="shipping__items2--content__desc">From handpicked sellers</p>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</section>-->
        <!-- End shipping section -->

    </main>
  @endsection