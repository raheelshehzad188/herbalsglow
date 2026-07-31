@php
    $trendingSection = $trendingSection ?? [];
    $trendingSectionIndex = $trendingSectionIndex ?? 0;

    if (empty($trendingSection['products'])) {
        return;
    }

    $sectionId = 'hp-module-trending-' . $trendingSectionIndex;
    $sliderId = 'trending-slider-' . $trendingSectionIndex;
    $viewAllId = 'trending-view-all-' . $trendingSectionIndex;
    $sectionTitle = $trendingSection['title'] ?? 'Trending now';
    $viewAllHref = $trendingSection['view_all'] ?? url('/shop');
    $products = $trendingSection['products'];
@endphp

@include('theme3/partials/dummy-home-assets')

<div id="{{ $sectionId }}" class="hp-module-trending">
    <div class="hp-module-background">
        <div class="container-fluid">
            <div class="hp-module-title">
                <div class="title-wrapper">
                    <b>{{ $sectionTitle }}</b>
                </div>
                <a class="view-all btn" id="{{ $viewAllId }}" href="{{ $viewAllHref }}">
                    <span>View all</span>
                </a>
            </div>

            <div id="{{ $sliderId }}" class="trending-slider">
                <button type="button" class="trending-slider__nav trending-slider__nav--prev" aria-label="Previous products">&#8249;</button>
                <div class="trending-slider__viewport">
                    <div class="trending-slider__track">
                        @foreach($products as $product)
                        <div class="trending-slider__slide">
                            @include('theme3/partials/product-box-dummy', ['product' => $product])
                        </div>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="trending-slider__nav trending-slider__nav--next" aria-label="Next products">&#8250;</button>
            </div>
        </div>
    </div>
</div>
