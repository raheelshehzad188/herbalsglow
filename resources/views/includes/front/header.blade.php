
<!-- Topbar Start -->
<?php $setting = DB::table('setting')
            ->where('id', '=', '1')
            ->first();
            $cate = DB::table('categories')->get();
            ?>
    <div class="container-fluid">
        
<div class="row bg-secondary py-2 px-xl-5 align-items-center">
            <div class="col-lg-6 d-lg-block hide_order">
                <div class="d-inline-flex align-items-center">
                    <a  class="text-white" href="#">Order Tracking </a>
                    <span class="text-white" px-2"> | </span>
                    <a class="text-white" href="#">Returns / Exchange</a>
                    <span class="text-white px-2"> | </span>
                    <a class="text-white" href="#">Payment Method</a>
                </div>
            </div>
            <div class="col-lg-6">
            <p class="welcome text-white text-right m-0">Welcome To Our Online Shopping Store</p>
            <a href="#" class=" hide_contact btn border text-white"><i class="fa fa-phone-alt text-primary mr-3" style="font-size:20px;"></i> 0331 2224449</a>
            </div>
        </div>
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="/" class="text-decoration-none" title="logo">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><img alt="" src="{{env('APP_URL')}}{{$setting->logo}}" width="200px" height="70px"></h1>
                </a>
            </div>
            <div class="col-lg-6 col-6 text-left">
                <form action="/search" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="text" placeholder="I’m shopping for..." required>
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary" type="submit" title="search">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 col-6 text-right">
               <a href="#" class="btn border  phone_contact"><i class="fa fa-phone-alt text-primary mr-3" style="font-size:20px;"></i> 0331 2224449</a>
                <a href="/cart" class="btn border" title="cart">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    <span class="badge" id="cartValue">{{ Session::has('cart') ? App\Helpers\Cart::qty() : 0 }}</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    
    <!-- Navbar End -->