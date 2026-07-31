
<!-- Topbar Start -->
<?php $setting = DB::table('setting')
            ->where('id', '=', '1')
            ->first();
            $cate = DB::table('categories')->where('status', 1)->orderBy('sort', 'ASC')->orderBy('id', 'DESC')->get();
            // Load subcategories for each category
            foreach($cate as $k => $v) {
                $cate[$k]->subcategories = DB::table('sub_categories')->where('category_id', $v->id)->get();
            }
            $header_menu = DB::table('pages')
    ->where('menu_type', '=', 'header')->where('status','=','1')->orderBy('position', 'asc')
    ->get();
            ?>
<div class="nav">
	<div class="container">
		<div class="inside-nav">
			<div class="marquee">
			   <p>{{ isset($setting->welcome_message) && !empty($setting->welcome_message) ? $setting->welcome_message : 'Welcome To QuickOn.Pk Online Web Store | we offer Free Delivery over purchase of Rs. 5000 all over Pakistan.' }}</p>
			</div>
			<div class="nav-col-right">
				<ul>
					@if(isset($setting->whatsapp) && !empty($setting->whatsapp))
					<li> <a href="https://wa.me/{{ $setting->whatsapp }}" target="_blank"> <i class="fa-brands fa-whatsapp"></i> {{ $setting->whatsapp }} </a> </li>
					@endif
					@if(isset($setting->track_order_link) && !empty($setting->track_order_link) && $setting->track_order_link != '#')
					<li> <a href="{{ $setting->track_order_link }}" target="_blank"> Track Order </a> </li>
					@endif
					@if(isset($setting->about_us_link) && !empty($setting->about_us_link) && $setting->about_us_link != '#')
					<li> <a href="{{ $setting->about_us_link }}" target="_blank"> About Us </a> </li>
					@endif
					@if(isset($setting->contact_us_link) && !empty($setting->contact_us_link) && $setting->contact_us_link != '#')
					<li> <a href="{{ $setting->contact_us_link }}" target="_blank"> Contact Us </a> </li>
					@endif
				</ul>
			</div>
		</div><!--inside-nav-->
	</div><!--container-->
</div><!--nav-->

<!--header-->        
	<div class="sticky-header" id="sticky-header">
        <div class="header">
            <div class="container">
                <div class="inside-header">
                    <div class="header-logo">
                        <a href="{{url('/')}}"> <img src="{{config('app.url')}}{{isset($setting) && $setting ? $setting->logo : ''}}" alt="logo"  style="object-fit: cover;"></a>
                    </div><!--header-logo-->
                    <div class="header-search">
					    <div class="in-hdr-srch">
							<form action="{{ url('/search') }}" method="GET" id="searchForm">
							  <div class="search-wrapper">
								<input type="text" name="q" placeholder="search your products" value="{{ request('q') }}" id="searchInput">
								<i class="fa-solid fa-magnifying-glass" onclick="document.getElementById('searchForm').submit()"></i>
							  </div>
							  <button type="submit" style="display: none;"></button>
							</form>
					  </div><!--in-hdr-srch-->
					</div><!--header-search-->

                    <div class="header-login-section">
                        <ul>
                            <!--<li> <a href="#"> Sign In or Sign Up <i class="fa-solid fa-chevron-down"></i> <img src="{{ $assets_url }}img/reshot-icon-user-QLCUYJBKM3.svg"></a> </li>-->
                            <li> <a href="#" onlick="updateCartSidebar()" class="openCart"> My Cart <span class="cart-count">{{ Session::has('cart') ? App\Helpers\Cart::qty() : 0 }}</span><img src="{{ $assets_url }}img/reshot-icon-shopping-cart-WFDT3CVZMJ.svg" name="Reshot Icon" width="24" height="24" alt="Best Shopping"></a> </li>
                        </ul>
                    </div>
                </div><!--inside-header-->
            </div><!--container-->
        </div><!--header-->
		
		
			<!--category-section-top-->
		
		<div class="category-section-top">
            <div class="container">
                <div class="inside-cat-sec-top">
                    <div class="category-button-top">
                        <button id="openMenuDesktop"><i class="fa-solid fa-align-left"></i> All Categories</button>
                    </div><!--category-button-top-->
                    <div class="cat-sec-menu">
                        <ul>
                            @foreach($header_menu as $k=> $v)
                            <li> <a href="
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
                                ">{{$v->name}}</a> </li>
                            @endforeach
                        </ul>
                    </div><!--cat-sec-menu-->
                </div><!--inside-cat-sec-top-->
            </div><!--container-->
        </div><!--category-section-top-->
    </div><!--sticky-header--> 

	<!--nav-mob-->

	<div class="nav-mob hidden-nav" id="hidden-nav">
		<div class="container">
			<div class="inside-nav-mob">
				<div class="marquee-mob">
				   <p>{{ isset($setting->welcome_message) && !empty($setting->welcome_message) ? $setting->welcome_message : 'Welcome To QuickOn.Pk Online Web Store | we offer Free Delivery over purchase of Rs. 5000 all over Pakistan.' }}</p>
				</div>
			</div><!--inside-nav-->
		</div><!--container-->
	</div><!--nav-->
    
<!--mobile-header-->
	<div class="sticky-header-mob" id="sticky-header-mob">
        <div class="mob-header">
            <div class="container">
                <div class="inside-mob-header">
                    <div class="header-mob-menu">
                        <i id="openMenuMobile" class="fa-solid fa-bars"></i>
                    </div><!--header-logo-->
                    <div class="header-mob-logo">
                        <a href="{{env('APP_URL')}}"> <img src="{{ img_url(isset($setting) && $setting ? $setting->logo : '') }}" alt="logo" style="object-fit: cover;"> </a>
                    </div><!--header-logo-->
                    <div class="header-mob-cart">             
                        <a href="#" class="openCart" aria-label="Open Cart"> <img src="{{ $assets_url }}img/cart-mob.svg" alt="Open Cart" width="24" height="24"></a>
                    </div><!--inside-search-->
                    <div class="header-mob-search" id="openMobileSearch">                
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div><!--inside-search-->
                </div><!--inside-mob-header-->
            </div><!--container-->
        </div><!--mob-header-->
	</div><!--sticky-header-->
       
	<!-- Mobile Search Modal -->
	<div id="mobileSearchModal" class="mobile-search-modal">
		<div class="mobile-search-content">
			<div class="mobile-search-header">
				<h3>Search</h3>
				<span id="closeMobileSearch" class="close-mobile-search">&times;</span>
			</div>
			<div class="mobile-search-body">
				<form action="{{ url('/search') }}" method="GET" id="mobileSearchForm">
					<div class="mobile-search-wrapper">
						<input type="text" name="q" id="mobileSearchInput" placeholder="Search your products" autocomplete="off">
						<i class="fa-solid fa-magnifying-glass" onclick="document.getElementById('mobileSearchForm').submit()"></i>
					</div>
					<div id="mobileSearchResults" class="mobile-search-results"></div>
				</form>
			</div>
		</div>
		<div id="mobileSearchOverlay" class="mobile-search-overlay"></div>
	</div>
	<!-- End Mobile Search Modal -->
	   
        <div id="cartSidebar" class="cart-sidebar">
            <div class="cart-header">
                <h3>Shopping Cart</h3>
                <span id="closeCart">&times;</span>
            </div>
            <div class="cart-content">
                @if(Session::has('cart') && App\Helpers\Cart::qty() > 0)
                    <!-- Cart items will be loaded dynamically here -->
                    <p>{{ App\Helpers\Cart::qty() }} item(s) in cart</p>
                @else
                    <img src="{{ $assets_url }}img/cart-cut-icon.svg" alt="Cart Cut" width="24" height="24">
                    <p>No Products In The Cart.</p>
                @endif
                <button onclick="window.location.href='{{ url('/') }}'"> Return To Shop </button>
            </div>
        </div><!--cart-sidebar-->
        <div id="cartOverlay"></div>


        
<!--sidebar-->

        <div id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h3>OUR CATEGORIES</h3>
                <span id="closeMenu">&times;</span>
            </div>
			
            <ul>
				<li><a href="{{ url('/shop') }}">Shop</a></li>
				
                @foreach($cate as $k=> $v)	
                <li class="has-submenu">
                    <a href="{{ url('/')}}/{{$v->slug}}" class="category-link">{{$v->name}}</a>
                    @if(isset($v->subcategories) && count($v->subcategories) > 0)
                    <i class="fa-solid fa-caret-down toggle-submenu"></i>
                    <ul class="submenu">
                        @foreach($v->subcategories as $sub)
                        <li><a href="{{ url('/')}}/{{$sub->slug}}">{{$sub->name}}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach  
                
            </ul>
        </div><!--sidebar-->
        <div id="overlay"></div>