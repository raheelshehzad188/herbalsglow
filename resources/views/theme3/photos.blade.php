@php
    $promoImages = [];
    if (!empty($setting->homepage_image_one)) $promoImages[] = $setting->homepage_image_one;
    if (!empty($setting->homepage_image_two)) $promoImages[] = $setting->homepage_image_two;
    if (!empty($setting->homepage_image_3)) $promoImages[] = $setting->homepage_image_3;
    if (!empty($setting->homepage_image_4)) $promoImages[] = $setting->homepage_image_4;
@endphp

@if(count($promoImages) > 0)
<section class="t3-section" style="padding-top:0;">
    <div class="container">
        <h2 class="t3-section-title">Special <span>Offers</span></h2>
        <div class="t3-promo-row">
            @foreach($promoImages as $img)
            <div class="t3-promo-item">
                <img src="{{ img_url($img) }}" alt="Promo banner">
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('theme3/recent')

@if(!empty($setting->homepage_image_5))
<section class="t3-section" style="padding-top:0;">
    <div class="container">
        <div class="t3-promo-item">
            <img src="{{ img_url($setting->homepage_image_5) }}" alt="Promo banner">
        </div>
    </div>
</section>
@endif

@if(!empty($setting->homepage_image_6))
<section class="t3-section" style="padding-top:0;">
    <div class="container">
        <div class="t3-promo-item">
            <img src="{{ img_url($setting->homepage_image_6) }}" alt="Promo banner">
        </div>
    </div>
</section>
@endif
