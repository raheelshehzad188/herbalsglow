@if(isset($featured_categories) && count($featured_categories) > 0)
<link rel="stylesheet" href="{{ asset('theme3/css/t3-product-box.css') }}">
<link rel="stylesheet" href="{{ asset('theme3/css/t3-product-slider.css') }}">

@foreach($featured_categories as $trendingSectionIndex => $category)
    @if(isset($category->products) && count($category->products) > 0)
        @php
            $sectionId = 'hp-module-trending-' . $trendingSectionIndex;
            $carouselId = 'carousel-trending-' . $trendingSectionIndex;
            $carouselInnerId = 'trending-inner-' . $trendingSectionIndex;
            $viewAllId = 'trending-view-all-' . $trendingSectionIndex;
            $categoryUrl = url('/' . $category->slug);
        @endphp
        <div id="{{ $sectionId }}" class="hp-module-trending">
            <div class="hp-module-background">
                <div class="container-fluid">
                    <div class="hp-module-title">
                        <div class="title-wrapper">
                            <b>{{ $category->name }}</b>
                        </div>
                        <a class="view-all btn" id="{{ $viewAllId }}" href="{{ $categoryUrl }}">
                            <span>View all</span>
                        </a>
                    </div>

                    <div class="carousel-container product-carousel home-module">
                        <div class="row">
                            <div class="col-xs-24 no-padding-x">
                                <div class="carousel">
                                    <div id="{{ $carouselId }}"
                                         class="carousel slide iherb-carousel-items clearfix t3-product-carousel"
                                         data-lazyload="product"
                                         data-interval="false">
                                        <div id="{{ $carouselInnerId }}"
                                             class="carousel-inner product-carousels rounded-product-cells col-xs-24 col-md-24">
                                            @foreach($category->products as $product)
                                                @php
                                                    $v = $product;
                                                    $productColClass = 'col-xs-24 col-md-12 col-lg-4';
                                                @endphp
                                                @include('theme3.product_box_new')
                                            @endforeach
                                        </div>

                                        <a class="left carousel-control"
                                           href="#"
                                           role="button"
                                           data-slider-prev
                                           aria-label="Previous products">
                                            <span class="scroll-icon scroll-l">
                                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <use xlink:href="#icon-chevron-left" fill="#333333"></use>
                                                </svg>
                                            </span>
                                        </a>
                                        <a class="right carousel-control"
                                           href="#"
                                           role="button"
                                           data-slider-next
                                           aria-label="Next products">
                                            <span class="scroll-icon scroll-r">
                                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <use xlink:href="#icon-chevron-right" fill="#333333"></use>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<script src="{{ asset('theme3/js/t3-product-slider.js') }}"></script>
@endif
