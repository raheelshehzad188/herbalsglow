<div class="container-fluid pt-5 pb-3">
        <div class="row px-xl-5">
            
                @if(isset($setting->homepage_image_5) && $setting->homepage_image_5 && file_exists($setting->homepage_image_5))
            <div class="col-md-6">
            <div class="product-offer mb-30" style="height: 200px;">
                <img class="img-fluid" src="{{ $setting->homepage_image_5 }}" alt="">
                <div class="offer-text">
                    {!! $setting->homepage_img5d !!}
                </div>
            </div>
            </div>
            @endif
            
                @if(isset($setting->homepage_image_6) && $setting->homepage_image_6 && file_exists($setting->homepage_image_6))
            <div class="col-md-6">
            <div class="product-offer mb-30" style="height: 200px;">
                <img class="img-fluid" src="{{ $setting->homepage_image_6 }}" alt="">
                <div class="offer-text">
                    {!! $setting->homepage_img6d !!}
                </div>
            </div>
            </div>
            @endif
        </div>
    </div>