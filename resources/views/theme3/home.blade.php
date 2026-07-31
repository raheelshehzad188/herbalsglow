@extends($layout)
@section('content')
@php
    $sliderBanners = theme3_slider_banners($Slider ?? collect());
    if (empty($sliderBanners)) {
        $sliderBanners = array_slice(theme3_dummy('slider_banners') ?? [], 0, 7);
    }
    $sliderCount = count($sliderBanners);
@endphp

@push('styles')
@include('theme3.partials.home-index-styles')
<style>
    #iherb-homepage .hp-bento-banners,
    #iherb-homepage .hp-quality-promise,
    #iherb-homepage .home-carousel-container,
    #iherb-homepage .hp-module-trending,
    #iherb-homepage .flash-deals-carousel-wrapper,
    #iherb-homepage .hp-banners-v2.using-banner-tool {
        display: block !important;
    }
    #iherb-homepage .hp-banners-v2 .homepage-banner.show {
        display: block !important;
    }
</style>
@endpush

<script>window.translations = window.translations || {}</script>
<script>window.translations.brandOfWeekCountDownText = "Offer ends in {0} days, {1} hours"</script>

<section id="iherb-homepage" class="home-page-refresh-enabled">
<script>
    window.isBentoEnabled = true;
    window.scaleWrapper = "&lt;div&#xA;  style=&quot;aspect-ratio: {sourceWidth}/{sourceHeight}; overflow: hidden; width: {targetWidth}px&quot;&#xA;&gt;&#xA;  &lt;div&#xA;    style=&quot;&#xA;      aspect-ratio: {sourceWidth}/{sourceHeight};&#xA;      width: {sourceWidth}px;&#xA;      transform: scale(calc({targetWidth} / {sourceWidth}));&#xA;      transform-origin: top left;&#xA;    &quot;&#xA;  &gt;&#xA;    {content}&#xA;  &lt;/div&gt;&#xA;&lt;/div&gt;";
</script>

@php theme3_section('bento-banners', compact('sliderBanners', 'sliderCount')); @endphp
@php theme3_section('braze-sdk'); @endphp
@php theme3_section('banners-v2'); @endphp

<div class="home-carousel-container hp-carousel-container">
    <div class="hp-modules">

        @php theme3_section('sign-in-prompt'); @endphp
        @php theme3_section('quality-promise'); @endphp
        @php theme3_section('recently-viewed'); @endphp
        @php theme3_section('vitacost-brands'); @endphp
        @php theme3_section('recommendations'); @endphp
        @php theme3_section('buy-it-again'); @endphp
        @php theme3_section('super-deals'); @endphp
        @php theme3_section('bogo'); @endphp
        @php theme3_section('shop-by-category'); @endphp
        @php theme3_section('product-highlight'); @endphp
        @php theme3_section('product-bundle-collections'); @endphp
        @php theme3_section('selected-for-you'); @endphp
        @php theme3_section('shop-by-health-topic'); @endphp
        @php theme3_section('inspired-by'); @endphp
        @php theme3_section('my-list'); @endphp
        @php theme3_section('more-items-to-consider'); @endphp
        @php theme3_section('flash-deals'); @endphp

        @include('theme3.partials.trending-categories')

        @php theme3_section('brands-of-the-week'); @endphp
        @php theme3_section('best-selling'); @endphp
        @php theme3_section('new-arrivals'); @endphp

    </div>
</div>

@php theme3_section('product-template'); @endphp

</section>

<div id="catalog-footer"></div>

@push('scripts')
<script>
    window.isWelcomeMatHighlightsEnabled = "True";
    window.iHerbLiveWhiteList = ["/","/c","/pr"];
    window.wellnessChatPayloadRoundLimit = 5;
</script>
@endpush
@endsection
