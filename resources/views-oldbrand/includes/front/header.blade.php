
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
            <a href="#" class="d-none hide_contact btn border text-white"><i class="fa fa-phone-alt text-primary mr-3" style="font-size:20px;"></i> 0331 2224449</a>
            </div>
        </div>
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="/" class="text-decoration-none" title="logo">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><img alt="" src="{{asset('')}}{{$setting->logo}}" width="200px" height="70px"></h1>
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
    <div class="container-fluid mb-5">
        <div class="row border-top px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" title="toggle" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0">Categories</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse show navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0" id="navbar-vertical">
                    <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                        @foreach($cate as $v)
                        <a href="/category/{{$v->slug}}" class="nav-item nav-link">{{$v->name}}</a>
                        @endforeach
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="/" class="text-decoration-none d-block d-lg-none"  title="logo">
                        <h1 class="m-0 display-5 font-weight-semi-bold"><img alt="" src="{{asset('')}}{{$setting->logo}}" width="150px" height="80px"></h1>
                    </a>
                    <button type="button" class="navbar-toggler" title="navbar" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="/" class="nav-item nav-link">Home</a>
                            <a href="/shop" class="nav-item nav-link">Shop</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" title="cart" data-toggle="dropdown">Shopping Cart</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    <a href="/cart" class="dropdown-item">Cart</a>
                                    <a href="/checkout" class="dropdown-item">Checkout</a>
                                </div>
                            </div>
                            <a href="/page/about" class="nav-item nav-link">About Us</a>
                            <a href="/contact" class="nav-item nav-link">Contact</a>
                        </div>
                        
                    </div>
                </nav>
                <div id="header-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($Slider as $key => $slide)
                        <div class="carousel-item active" style="height: 410px;">
                            <img class="img-fluid" src="{{asset('')}}public/img/slider/{{$slide->slider_image}}" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3">10% Off Your First Order</h4>
                                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">Fashionable Dress</h3>
                                    <a href="/shop" class="btn btn-light py-2 px-3">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" title="prev" href="#header-carousel" data-slide="prev">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-prev-icon mb-n2"></span>
                        </div>
                    </a>
                    <a class="carousel-control-next" title="mext" href="#header-carousel" data-slide="next">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-next-icon mb-n2"></span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar End -->