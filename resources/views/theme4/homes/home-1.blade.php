@php
    $shopUrl = url('/shop');
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
    $slides = collect($Slider ?? []);
    $brands = \App\Models\Admins\Brand::query()->orderBy('id')->get();
    $dummyCats = [
        ['Dresses', 'dresses.webp'],
        ['Leather Bags', 'bags.webp'],
        ['Sweaters', 'sweaters.webp'],
        ['Boots', 'shoes.webp'],
        ['Gift for Him', 'gift.webp'],
        ['Sneakers', 'sneakers.webp'],
        ['Watch', 'watch.webp'],
        ['Gold Rings', 'ring.webp'],
        ['Cap', 'cap.webp'],
        ['Sunglass', 'glass.webp'],
        ['Baby Shop', 'baby.webp'],
        ['Leather Bags', 'bags.webp'],
    ];
@endphp

@if(theme_setting('hero.enabled', true))
<section id="hero" class="hero">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper hero-wrapper">
            @forelse($slides as $i => $slide)
                @php
                    $slideClass = ['hero-slider-one', 'hero-slider-two', 'hero-slider-three'][$i % 3];
                    $bg = storefront_img($slide->slider_image ?? $slide->image ?? '', $t4 . '/hero-slider-one.webp');
                @endphp
                <div class="swiper-slide {{ $slideClass }}" style="background-image:url('{{ $bg }}');background-size:cover;background-position:center;">
                    <div class="container">
                        <div class="col-lg-6">
                            <div class="wrapper-section" data-aos="fade-up">
                                <div class="wrapper-info">
                                    <h5 class="wrapper-subtitle">{!! $slide->heading ?? 'UP TO <span class="wrapper-inner-title">70%</span> OFF' !!}</h5>
                                    <h1 class="wrapper-details">{{ $slide->p ?? 'Fashion Collection Summer Sale' }}</h1>
                                    @if(theme_setting('hero.show_button', true))
                                    <a href="{{ $slide->image_url ?: $shopUrl }}" class="shop-btn">{{ $slide->button ?: 'Shop Now' }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                @foreach(['hero-slider-one','hero-slider-two','hero-slider-three'] as $cls)
                <div class="swiper-slide {{ $cls }}">
                    <div class="container">
                        <div class="col-lg-6">
                            <div class="wrapper-section" data-aos="fade-up">
                                <div class="wrapper-info">
                                    <h5 class="wrapper-subtitle">UP TO <span class="wrapper-inner-title">70%</span> OFF</h5>
                                    <h1 class="wrapper-details">Fashion Collection Summer Sale</h1>
                                    @if(theme_setting('hero.show_button', true))
                                    <a href="{{ $shopUrl }}" class="shop-btn">Shop Now</a>
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

<section class="product fashion-style">
    <div class="container">
        <div class="style-section">
            <div class="row gy-4 gx-5 gy-lg-0">
                <div class="col-lg-6">
                    <div class="product-wrapper wrapper-one" data-aos="fade-right">
                        <div class="wrapper-info">
                            <span class="wrapper-subtitle">NEW STYLE</span>
                            <h4 class="wrapper-details">Get 65% Offer
                                <span class="wrapper-inner-title">& Make New</span> Fusion.
                            </h4>
                            <a href="{{ $shopUrl }}" class="shop-btn">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-wrapper wrapper-two" data-aos="fade-up">
                        <div class="wrapper-info">
                            <span class="wrapper-subtitle">Mega OFFER</span>
                            <h4 class="wrapper-details">
                                Make your New
                                <span class="wrapper-inner-title">Styles with Our</span>
                                Products
                            </h4>
                            <a href="{{ $shopUrl }}" class="shop-btn">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(theme_setting('categories.enabled', true))
<section class="product-category">
    <div class="container">
        <div class="section-title">
            <h5>Our Categories</h5>
            <a href="{{ $shopUrl }}" class="view">View All</a>
        </div>
        <div class="category-section">
            @if($cats->count())
                @foreach($cats->take(12) as $cat)
                <div class="product-wrapper" data-aos="fade-right">
                    <div class="wrapper-img">
                        <a href="{{ url('/category/' . $cat->slug) }}">
                            <img src="{{ storefront_img($cat->image ?? '', $t4 . '/category-img/dresses.webp') }}" alt="{{ $cat->name }}">
                        </a>
                    </div>
                    <div class="wrapper-info">
                        <a href="{{ url('/category/' . $cat->slug) }}" class="wrapper-details">{{ $cat->name }}</a>
                    </div>
                </div>
                @endforeach
            @else
                @foreach($dummyCats as $dc)
                <div class="product-wrapper" data-aos="fade-right">
                    <div class="wrapper-img">
                        <a href="{{ $shopUrl }}"><img src="{{ $t4 }}/category-img/{{ $dc[1] }}" alt="{{ $dc[0] }}"></a>
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

<section class="product brand" data-aos="fade-up">
    <div class="container">
        <div class="section-title">
            <h5>Brand of Products</h5>
            <a href="{{ $shopUrl }}" class="view">View All</a>
        </div>
        <div class="brand-section">
            @if($brands->count())
                @foreach($brands as $brand)
                <div class="product-wrapper">
                    <div class="wrapper-img">
                        <a href="{{ $shopUrl }}">
                            <img src="{{ storefront_img($brand->image ?? '', $t4 . '/brand-img-1.webp') }}" alt="{{ $brand->name }}">
                        </a>
                    </div>
                </div>
                @endforeach
            @else
                @for($i = 1; $i <= 12; $i++)
                <div class="product-wrapper">
                    <div class="wrapper-img">
                        <a href="{{ $shopUrl }}"><img src="{{ $t4 }}/brand-img-{{ $i }}.webp" alt="brand"></a>
                    </div>
                </div>
                @endfor
            @endif
        </div>
    </div>
</section>

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

@if(theme_setting('homepage.show_sale', true))
<section class="product flash-sale">
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
        <div class="style-section">
            <div class="row gy-4 gx-5 gy-lg-0">
                <div class="col-lg-6">
                    <div class="product-wrapper wrapper-one" data-aos="fade-right">
                        <div class="wrapper-info">
                            <span class="wrapper-subtitle">NEW STYLE</span>
                            <h4 class="wrapper-details">Get 65% Offer <span class="wrapper-inner-title">& Make New</span> Fusion.</h4>
                            <a href="{{ $shopUrl }}" class="shop-btn">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-wrapper wrapper-two" data-aos="fade-up">
                        <div class="wrapper-info">
                            <span class="wrapper-subtitle">Mega OFFER</span>
                            <h4 class="wrapper-details">Make your New <span class="wrapper-inner-title">Styles with Our</span> Products</h4>
                            <a href="{{ $shopUrl }}" class="shop-btn">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="product best-product">
    <div class="container">
        <div class="section-title">
            <h5>Flash Sale</h5>
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
