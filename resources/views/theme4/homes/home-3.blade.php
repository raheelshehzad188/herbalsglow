@php
    $shopUrl = url('/shop');
    $t2 = asset('theme4/assets/images/homepage-two');
    $t3 = asset('theme4/assets/images/homepage-three');
    $featured = collect($fproducts ?? []);
    $arrivals = collect($aproducts ?? []);
    $sale = collect($onslaeproducts ?? []);
    $top = collect($mostviewproducts ?? []);
    $all = collect($products ?? []);
    if ($featured->isEmpty()) { $featured = $arrivals; }
    if ($arrivals->isEmpty()) { $arrivals = $all->take(8); }
    if ($sale->isEmpty()) { $sale = $arrivals->take(4); }
    if ($top->isEmpty()) { $top = $all->take(6); }
    $weekly = $sale->take(4);
    $flashGrid = $all->take(12);
    $cats = collect($featured_categories ?? $categories ?? []);
    $slides = collect($Slider ?? []);
    $brands = \App\Models\Admins\Brand::query()->orderBy('id')->get();
    $dummyCats = [
        ['Women Shoes', 'cat-img-1.webp'],
        ['Leather Bags', 'cat-img-8.webp'],
        ['Sunglasses', 'cat-img-2.webp'],
        ['Gold Ring', 'cat-img-3.webp'],
        ['Makeup Box', 'cat-img-4.webp'],
        ['Watches', 'cat-img-5.webp'],
        ['Sweaters', 'cat-img-6.webp'],
        ['Shoes', 'cat-img-7.webp'],
    ];
@endphp

@if(theme_setting('hero.enabled', true))
<section id="hero" class="hero hero-three">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper hero-wrapper">
            @php $slideClasses = ['hero-slider-one','hero-slider-two','hero-slider-three']; @endphp
            @forelse($slides as $i => $slide)
                <div class="swiper-slide {{ $slideClasses[$i % 3] }}">
                    <div class="container">
                        <div class="col-lg-6">
                            <div class="wrapper-section" data-aos="fade-up">
                                <div class="wrapper-info">
                                    <h5 class="wrapper-subtitle">Classic Exclusive</h5>
                                    <h1 class="wrapper-title">Summer’s Collection</h1>
                                    <h4 class="wrapper-details">Up to 40% OFF</h4>
                                    @if(theme_setting('hero.show_button', true))
                                    <a href="{{ $slide->image_url ?: $shopUrl }}" class="shop-btn">{{ $slide->button ?: 'Go Shopping' }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                @foreach($slideClasses as $cls)
                <div class="swiper-slide {{ $cls }}">
                    <div class="container">
                        <div class="col-lg-6">
                            <div class="wrapper-section">
                                <div class="wrapper-info">
                                    <h5 class="wrapper-subtitle">Classic Exclusive</h5>
                                    <h1 class="wrapper-title">Summer’s Collection</h1>
                                    <h4 class="wrapper-details">Up to 40% OFF</h4>
                                    @if(theme_setting('hero.show_button', true))
                                    <a href="{{ $shopUrl }}" class="shop-btn">Go Shopping</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>
@endif

@if(theme_setting('categories.enabled', true))
<section class="product-category product-category-two">
    <div class="container">
        <div class="section-title">
            <h5>Our Categories</h5>
            <a href="{{ $shopUrl }}" class="view">View All</a>
        </div>
        <div class="category-section category-section-two">
            @if($cats->count())
                @foreach($cats->take(12) as $cat)
                <div class="product-wrapper" data-aos="fade-right">
                    <div class="wrapper-img">
                        <a href="{{ url('/category/' . $cat->slug) }}"><img src="{{ img_url($cat->image) }}" alt="{{ $cat->name }}"></a>
                    </div>
                    <div class="wrapper-info">
                        <a href="{{ url('/category/' . $cat->slug) }}" class="wrapper-details">{{ $cat->name }}</a>
                    </div>
                </div>
                @endforeach
            @else
                @foreach($dummyCats as $dc)
                <div class="product-wrapper">
                    <div class="wrapper-img">
                        <a href="{{ $shopUrl }}"><img src="{{ $t2 }}/{{ $dc[1] }}" alt="{{ $dc[0] }}"></a>
                    </div>
                    <div class="wrapper-info">
                        <a href="{{ $shopUrl }}" class="wrapper-details">{{ $dc[0] }}</a>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
@endif

@if(theme_setting('homepage.show_featured', true))
<section class="product arrival">
    <div class="container">
        <div class="section-title">
            <h5>NEW ARRIVALS</h5>
            <a href="{{ $shopUrl }}" class="view">View All</a>
        </div>
        <div class="arrival-section">
            <div class="row g-5">
                @foreach($arrivals->take(8) as $v)
                <div class="col-lg-3 col-sm-6">
                    @include('theme4.product_box')
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<section class="product discount-two" data-aos="fade-right">
    <div class="container">
        <div class="discount-section discount-section-two">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="section-content" style="background: url({{ $t3 }}/discount-img-1.webp) no-repeat center / cover;">
                        <p class="subtitle">New Style</p>
                        <h3 class="wrapper-title">Get <span class="inner-text">65% Offer</span> <br> & Make New <br> Fusion.</h3>
                        <a href="{{ $shopUrl }}" class="shop-btn">Shop Now</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-content" style="background: url({{ $t3 }}/discount-img-2.webp) no-repeat center / cover;">
                        <p class="subtitle">New Style</p>
                        <h3 class="wrapper-title">Get <span class="inner-text">65% Offer</span> <br> & Make New <br> Fusion.</h3>
                        <a href="{{ $shopUrl }}" class="shop-btn">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(theme_setting('homepage.show_sale', true))
<section class="product flash-sale flash-sale-three">
    <div class="container">
        <div class="section-title">
            <h5>Flash Sale</h5>
            <div class="countdown-section">
                <div class="countdown-items"><span id="day" class="number">0</span><span class="text">Days</span></div>
                <div class="countdown-items"><span id="hour" class="number">0</span><span class="text">Hours</span></div>
                <div class="countdown-items"><span id="minute" class="number">0</span><span class="text">Minutes</span></div>
                <div class="countdown-items"><span id="second" class="number">0</span><span class="text">seconds</span></div>
            </div>
            <a href="{{ $shopUrl }}" class="view">View All</a>
        </div>
        <div class="flash-sale-section">
            <div class="row g-5">
                @foreach($sale->take(4) as $v)
                <div class="col-lg-3 col-md-6">
                    @include('theme4.product_box')
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<section class="product top-selling">
    <div class="container">
        <div class="section-title">
            <h5>Top Selling Products</h5>
            <a href="{{ $shopUrl }}" class="view">View All</a>
        </div>
        <div class="top-selling-section">
            <div class="row g-5">
                @foreach($top->take(6) as $v)
                <div class="col-lg-4 col-md-6">
                    @include('theme4.product_box')
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="product weekly-sale">
    <div class="container">
        <div class="section-title">
            <h5>Best Sell in this Week</h5>
            <a href="{{ $shopUrl }}" class="view">View All</a>
        </div>
        <div class="weekly-sale-section">
            <div class="row g-5">
                @foreach($weekly as $v)
                <div class="col-lg-3 col-md-6">
                    @include('theme4.product_box')
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="product best-product">
    <div class="container">
        <div class="section-title">
            <h5>Best Products</h5>
            <a href="{{ $shopUrl }}" class="view">View All</a>
        </div>
        <div class="best-product-section">
            <div class="row g-4">
                @foreach($flashGrid as $v)
                <div class="col-xl-2 col-md-4">
                    @include('theme4.product_box')
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
