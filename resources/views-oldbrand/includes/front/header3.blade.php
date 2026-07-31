<!-- Topbar Start -->
<?php $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
    $header_menu = DB::table('pages')
    ->where('menu_type', '=', 'header')->where('status','=','1')->orderBy('position', 'asc')
    ->get();
    $top_bar = DB::table('pages')
    ->where('menu_type', '=', 'top_bar')->where('status','=','1')->orderBy('position', 'asc')
    ->get();
$cate = DB::table('categories')->get();
?>
        <!-- Topbar Start -->
<div class="container-fluid">
    <div class="row bg-secondary py-1 px-xl-5">
        <div class="col-lg-6 d-none d-lg-block">
            <div class="d-inline-flex align-items-center h-100">
                @foreach($top_bar as $k=> $v)
                        
                        <a href="
                        @if($v->route)
                        <?php
                        if($v->route == '/')
                        {
                        ?>
                        {{ '/'; }}
                        <?php
                        }
                        else
                        {
                        ?>
                        {{ url(''); }}/{{$v->route}}
                        <?php
                        }
                        ?>
                        @else
                        {{ url('/')}}/{{$v->slug}}
                        @endif
                        " class="text-body nav-item nav-link ">{{$v->name}}</a>
                        @endforeach
            </div>
        </div>
        <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center d-block d-lg-none">
                    <a href="{{ url('/my_wishlist') }}" class="btn px-0 ml-2">
                        <i class="fas fa-heart text-dark"></i>
                        <span class="badge text-dark border border-dark rounded-circle" style="padding-bottom: 2px;">{{ isset($_COOKIE['wishlist'])?count(json_decode($_COOKIE['wishlist'])):0 }}</span>
                    </a>
                    <a href="{{ url('/cart') }}" class="btn px-0 ml-2">
                        <i class="fas fa-shopping-cart text-dark"></i>
                        <span class="badge text-dark border border-dark rounded-circle" style="padding-bottom: 2px;">{{ Session::has('cart') ? App\Helpers\Cart::qty() : 0 }}</span>
                    </a>
                </div>
            </div>
    </div>
    <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
        <div class="col-lg-4">
            <a href="{{ url('/'); }}" class="text-decoration-none">
                <img alt="" style="object-fit: cover;" src="{{asset('')}}{{$setting->logo}}"  width="150px" height="80px">
            </a>
        </div>
        <div class="col-lg-4 col-6 text-left">
            <form action="{{ url('search'); }}">
                <div class="input-group">
                    <input type="text" name="text" class="form-control" placeholder="Search for products">
                    <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-4 col-6 text-right">
            <p class="m-0">Customer Service</p>
            <a href="https://wa.me/{{ $setting->phone }}"><h5 class="m-0">{{ $setting->phone }}</h5></a>
        </div>
    </div>
</div>
<!-- Topbar End -->


<!-- Navbar Start -->
<div class="container-fluid bg-dark mb-30">
    <div class="row px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
            <a class="btn d-flex align-items-center justify-content-between bg-primary w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; padding: 0 30px;">
                <h6 class="text-dark m-0"><i class="fa fa-bars mr-2"></i>Categories</h6>
                <i class="fa fa-angle-down text-dark"></i>
            </a>
            <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 999;">
                <div class="navbar-nav w-100">
                    @foreach($cate as $k=> $v)
                    @php
                    $scats = DB::table('sub_categories')->where('category_id',$v->id)->get();
                    @endphp
                    @if(count($scats))
                    <div class="nav-item dropdown dropright">
                        @php
                        @endphp
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{{$v->name}} <i class="fa fa-angle-right float-right mt-1"></i></a>
                            <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                                @foreach($scats as $kk=> $vv)
                                <a href="{{ url('/subcategory') }}/{{$vv->slug}}" class="dropdown-item">{{$vv->name}}</a>
                                @endforeach
                            </div>
                        </div>
                    @else
                    <a href="{{ url('/category') }}/{{$v->slug}}" class="nav-item nav-link">{{$v->name}}</a>
                    @endif
                    @endforeach
                </div>
            </nav>
        </div>
        <div class="col-lg-9">
            <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                <a href="{{ url(''); }}" class="text-decoration-none d-block d-lg-none">
                    <img alt="" style="object-fit: cover;" src="{{asset('')}}{{$setting->wlogo}}"  width="150px" height="80px">
                </a>
                    <div style="display: flex;">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                </div>
                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav mr-auto py-0">
                        @foreach($header_menu as $k=> $v)
                        
                        <a href="
                        @if($v->route)
                        <?php
                        if($v->route == '/')
                        {
                        ?>
                        {{ '/'; }}
                        <?php
                        }
                        else
                        {
                        ?>
                        {{ url(''); }}/{{$v->route}}
                        <?php
                        }
                        ?>
                        @else
                        {{ url('/')}}/{{$v->slug}}
                        @endif
                        " class="nav-item nav-link ">{{$v->name}}</a>
                        @endforeach
                    </div>
                    <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
                        <a href="{{ url('/my_wishlist') }}" class="btn px-0">
                            <i class="fas fa-heart text-primary"></i>
                            <span class="badge text-secondary border border-secondary rounded-circle" style="padding-bottom: 2px;">{{ isset($_COOKIE['wishlist'])?count(json_decode($_COOKIE['wishlist'])):0 }}</span>
                        </a>
                        <a href="{{ url('cart'); }}" class="btn px-0 ml-3">
                            <i class="fas fa-shopping-cart text-primary"></i>
                            <span class="badge text-secondary border border-secondary rounded-circle" style="padding-bottom: 2px;" id="cartValue">{{ Session::has('cart') ? App\Helpers\Cart::qty() : 0 }}</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
<!-- Navbar End -->