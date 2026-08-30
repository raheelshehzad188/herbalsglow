@php
    use App\Helpers\Cart;
    $site = $setting ?? DB::table('setting')->where('id', 1)->first();
    $homeLayout = storefront_home_layout($site);
    $headerClass = 'header';
    if ($homeLayout === 2) {
        $headerClass = 'header header-two';
    } elseif ($homeLayout === 3) {
        $headerClass = 'header header-two header-three';
    }
    $categories = $header_menu_categories ?? \App\Models\Admins\Category::where('status', 1)->orderBy('sort')->orderBy('id', 'desc')->get();
    $categories = collect($categories)->filter(function ($cat) {
        $img = (string) ($cat->image ?? '');
        return $img !== '' || (int) ($cat->show_on_home ?? 0) === 1;
    })->values();
    $cartQty = Cart::qty();
    $t4 = rtrim($assets_url ?? asset('theme4') . '/', '/') . '/assets/images';
    $logo = storefront_img($site->logo ?? '', $t4 . '/logos/logo.webp');
    $headerPages = $header_pages ?? collect();
    $topBarPages = $top_bar_pages ?? collect();
    $phone = $phone ?? ($site->phone ?? ($site->phonetwo ?? '+ 00645 4568'));
    $catFallback = $t4 . '/homepage-one/category-img/dresses.webp';
@endphp
<header id="header" class="{{ $headerClass }}">
    @if(theme_setting('header.announcement_bar', true))
    <div class="header-top-section">
        <div class="container">
            <div class="header-top">
                <div class="header-profile">
                    @forelse($topBarPages as $page)
                        <a href="{{ page_url($page) }}"><span>{{ $page->name }}</span></a>
                    @empty
                        <a href="{{ url('/track_order') }}"><span>Track Order</span></a>
                        <a href="{{ url('/faq') }}"><span>Support</span></a>
                    @endforelse
                </div>
                <div class="header-contact d-none d-lg-block">
                    <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">
                        <span>Need help? Call us:</span>
                        <span class="contact-number">{{ $phone }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="header-center-section d-none d-lg-block">
        <div class="container">
            <div class="header-center">
                @if(theme_setting('header.show_logo', true))
                <div class="logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ $logo }}" alt="{{ $site->site_title ?? 'Shopus' }}">
                    </a>
                </div>
                @endif
                <div class="header-cart-items">
                    @if(theme_setting('header.show_search', true))
                    <div class="header-search">
                        <button class="header-search-btn" type="button" onclick="typeof modalAction === 'function' && modalAction('.search')">
                            <span>
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.9708 16.4151C12.5227 17.4021 10.9758 17.9723 9.27353 18.0062C5.58462 18.0802 2.75802 16.483 1.05056 13.1945C-1.76315 7.77253 1.33485 1.37571 7.25086 0.167548C12.2281 -0.848249 17.2053 2.87895 17.7198 7.98579C17.9182 9.95558 17.5566 11.7939 16.5852 13.5061C16.4512 13.742 16.483 13.8725 16.6651 14.0553C18.2412 15.6386 19.8112 17.2272 21.3735 18.8244C22.1826 19.6513 22.2058 20.7559 21.456 21.4932C20.7697 22.1678 19.7047 22.1747 18.9764 21.4793C18.3623 20.8917 17.7774 20.2737 17.1796 19.6688C16.118 18.5929 15.0564 17.5153 13.9708 16.4151ZM2.89545 9.0364C2.91692 12.4172 5.59664 15.1164 8.91967 15.1042C12.2384 15.092 14.9138 12.3493 14.8889 8.98505C14.864 5.63213 12.1826 2.92508 8.89047 2.92857C5.58204 2.93118 2.87397 5.68958 2.89545 9.0364Z" fill="black"/></svg>
                            </span>
                        </button>
                        <div class="modal-wrapper search">
                            <div onclick="typeof modalAction === 'function' && modalAction('.search')" class="anywhere-away"></div>
                            <div class="modal-main">
                                <div class="wrapper-close-btn" onclick="typeof modalAction === 'function' && modalAction('.search')">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </span>
                                </div>
                                <div class="wrapper-main">
                                    <form class="search-section" action="{{ url('/search') }}" method="get">
                                        <input type="text" name="q" placeholder="Search Products........." value="{{ request('q') }}">
                                        <div class="divider"></div>
                                        <button type="button">All Categories</button>
                                        <button type="submit" class="shop-btn">Search</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(theme_setting('header.show_cart', true))
                    <div class="header-cart">
                        <a href="{{ url('/cart') }}" class="cart-item">
                            <span class="cart-text">Cart</span>
                            <span class="t4-cart-count">{{ $cartQty }}</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <nav class="mobile-menu d-block d-lg-none">
        <div class="mobile-menu-header d-flex justify-content-between align-items-center">
            <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
                <span>
                    <svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="14" height="1" fill="#1D1D1D"/><rect y="8" width="14" height="1" fill="#1D1D1D"/><rect y="4" width="10" height="1" fill="#1D1D1D"/></svg>
                </span>
            </button>
            <a href="{{ url('/') }}" class="mobile-header-logo">
                <img src="{{ $logo }}" alt="logo">
            </a>
            <a href="{{ url('/cart') }}" class="header-cart cart-item">
                <span class="cart-text">Cart</span>
                <span class="t4-cart-count">{{ $cartQty }}</span>
            </a>
        </div>
        <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions">
            <div class="offcanvas-body">
                <div class="header-top">
                    <div class="shop-btn">
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                </div>
                <form class="header-input" action="{{ url('/search') }}" method="get">
                    <input type="text" name="q" placeholder="Search....">
                </form>
                <div class="category-dropdown">
                    <ul class="category-list">
                        @foreach($categories as $cat)
                        <li class="category-list-item">
                            <a href="{{ url('/category/' . $cat->slug) }}">
                                <div class="dropdown-item d-flex justify-content-between align-items-center">
                                    <div class="dropdown-list-item d-flex">
                                        <span class="dropdown-img">
                                            <img src="{{ storefront_img($cat->image ?? '', $catFallback) }}" alt="{{ $cat->name }}">
                                        </span>
                                        <span class="dropdown-text">{{ $cat->name }}</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="header-bottom d-lg-block d-none">
        <div class="container">
            <div class="header-nav">
                @if(theme_setting('header.show_category_menu', true))
                <div class="category-menu-section position-relative">
                    <div class="empty position-fixed" onclick="typeof tooglmenu === 'function' && tooglmenu()"></div>
                    <button class="dropdown-btn" type="button" onclick="typeof tooglmenu === 'function' && tooglmenu()">
                        <span class="dropdown-icon">
                            <svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="14" height="1"/><rect y="8" width="14" height="1"/><rect y="4" width="10" height="1"/></svg>
                        </span>
                        <span class="list-text">All Categories</span>
                    </button>
                    <div class="category-dropdown position-absolute" id="subMenu">
                        <ul class="category-list">
                            @foreach($categories as $cat)
                            <li class="category-list-item">
                                <a href="{{ url('/category/' . $cat->slug) }}">
                                    <div class="dropdown-item">
                                        <div class="dropdown-list-item">
                                            <span class="dropdown-img">
                                                <img src="{{ storefront_img($cat->image ?? '', $catFallback) }}" alt="{{ $cat->name }}">
                                            </span>
                                            <span class="dropdown-text">{{ $cat->name }}</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                @if(theme_setting('header.show_menu', true))
                <div class="header-nav-menu">
                    <ul class="menu-list">
                        <li>
                            <a href="{{ url('/') }}"><span class="list-text">Home</span></a>
                        </li>
                        <li>
                            <a href="{{ url('/shop') }}"><span class="list-text">Shop</span></a>
                        </li>
                        @forelse($headerPages as $page)
                            <li><a href="{{ page_url($page) }}"><span class="list-text">{{ $page->name }}</span></a></li>
                        @empty
                            <li><a href="{{ url('/about') }}"><span class="list-text">About</span></a></li>
                            <li><a href="{{ url('/blog') }}"><span class="list-text">Blog</span></a></li>
                            <li><a href="{{ url('/faq') }}"><span class="list-text">FAQ</span></a></li>
                            <li><a href="{{ url('/contact') }}"><span class="list-text">Contact</span></a></li>
                        @endforelse
                    </ul>
                </div>
                @endif
                <div class="header-vendor-btn">
                    <a href="{{ url('/shop') }}" class="shop-btn">
                        <span class="list-text shop-text">Became Vendor</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
