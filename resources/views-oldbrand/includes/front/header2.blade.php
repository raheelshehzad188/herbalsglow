<!-- Topbar Start -->
<?php $setting = DB::table('setting')
            ->where('id', '=', '1')
            ->first();
            $cate = DB::table('categories')->get();
            ?>
    <div class="container-fluid">
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="/" class="text-decoration-none"  title="logo">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><img alt="" src="{{asset('')}}{{$setting->logo}}"  width="150px" height="80px"></h1>
                </a>
            </div>
            <div class="col-lg-6 col-6 text-left">
                <form action="/search" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="text" placeholder="I’m shopping for..." required>
                        <div class="input-group-append">
                            <button class="input-group-text bg-transparent text-primary" type="submit" title="searchh">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 col-6 text-right">
               
                <a href="/cart" class="btn border">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    <span class="badge" id="cartValue">{{ Session::has('cart') ? App\Helpers\Cart::qty() : 0 }}</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid">
        <div class="row border-top px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0">Categories</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 1;">
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
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="/" class="nav-item nav-link">Home</a>
                            <a href="/shop" class="nav-item nav-link">Shop</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Shopping Cart</a>
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
            </div>
        </div>
    </div>
    <!-- Navbar End -->