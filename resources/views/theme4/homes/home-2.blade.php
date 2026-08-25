@php
    $shopUrl = url('/shop');
    $t2 = asset('theme4/assets/images/homepage-two');
    $t4 = asset('theme4/assets/images/homepage-one');
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
    $brands = \App\Models\Admins\Brand::query()->orderBy('id')->get();
    $heroTiles = [
        ['Floral Dresses', $t2.'/h-3.webp'],
        ['Watch Collection', $t2.'/h-4.webp'],
        ['Men’s Sneakers', $t2.'/h-5.webp'],
    ];
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
<section id="hero" class="hero hero-two">
    <div class="container">
        <div class="hero-section-two">
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="hero-left hero-wrapper-two">
                        <div class="wrapper-content">
                            <h1 class="wrapper-title">New<br>Arrivals</h1>
                            <h5 class="wrapper-details">Special Collection</h5>
                            @if(theme_setting('hero.show_button', true))
                            <a href="{{ $shopUrl }}" class="shop-btn">Shop Now</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-right hero-wrapper-two">
                        <div class="wrapper-content">
                            <h2 class="wrapper-title">30%</h2>
                            <h5 class="wrapper-details">Summer Sale</h5>
                            @if(theme_setting('hero.show_button', true))
                            <a href="{{ $shopUrl }}" class="shop-btn">Shop Now</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-category-section">
            <div class="row g-5">
                @foreach($heroTiles as $tile)
                <div class="col-lg-4">
                    <div class="hero-wrapper" style="background: url({{ $tile[1] }}) no-repeat center / cover">
                        <div class="wrapper-content">
                            <h5 class="wrapper-details">{{ $tile[0] }}</h5>
                            <a href="{{ $shopUrl }}" class="shop-btn">Shop Now</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
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
                @foreach($cats->take(12) as $i => $cat)
                <div class="product-wrapper" data-aos="fade-right">
                    <div class="wrapper-img">
                        <a href="{{ url('/category/' . $cat->slug) }}">
                            <img src="{{ img_url($cat->image) }}" alt="{{ $cat->name }}">
                        </a>
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

<section class="product brand-two" data-aos="fade-up">
    <div class="container">
        <div class="section-title">
            <h5>Brand of Products</h5>
            <a href="{{ $shopUrl }}" class="view">View All</a>
        </div>
        <div class="brand-section">
            @if($brands->count())
                @foreach($brands->take(8) as $brand)
                <div class="product-wrapper">
                    <div class="wrapper-img">
                        <a href="{{ $shopUrl }}"><img src="{{ img_url($brand->image) }}" alt="{{ $brand->name }}"></a>
                    </div>
                </div>
                @endforeach
            @else
                @for($i = 1; $i <= 8; $i++)
                <div class="product-wrapper">
                    <div class="wrapper-img">
                        <a href="{{ $shopUrl }}"><img src="{{ $t2 }}/brand-img-{{ $i }}.webp" alt="brand"></a>
                    </div>
                </div>
                @endfor
            @endif
        </div>
    </div>
</section>

@if(theme_setting('homepage.show_featured', true))
<section class="product arrival arrival-two">
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

<section class="product discount" data-aos="zoom-in">
    <div class="container">
        <div class="discount-section">
            <div class="section-content">
                <p class="subtitle">New Style</p>
                <h3 class="wrapper-title">Get 65% <span class="inner-text">Offer</span> <br> & Make New Fusion.</h3>
                <a href="{{ $shopUrl }}" class="shop-btn">Shop Now</a>
            </div>
        </div>
    </div>
</section>

@if(theme_setting('homepage.show_sale', true))
<section class="product flash-sale flash-sale-two">
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

<section class="product weekly-sale weekly-sale-two">
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

<section class="product best-product best-product-two">
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
