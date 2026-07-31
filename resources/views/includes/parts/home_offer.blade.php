<div class="container-fluid pt-5 pb-3">
        <div class="row px-xl-5">
            
                @if(isset($setting->homepage_image_3) && $setting->homepage_image_3 && file_exists($setting->homepage_image_3))
            <div class="col-md-6">
            <div class="product-offer mb-30" style="height: 200px;">
                <img class="img-fluid" src="{{ $setting->homepage_image_3 }}" alt="">
                <div class="offer-text">
                    {!! $setting->homepage_img3d !!}
                </div>
            </div>
            </div>
            @endif
            
                @if(isset($setting->homepage_image_4) && $setting->homepage_image_4 && file_exists($setting->homepage_image_4))
            <div class="col-md-6">
            <div class="product-offer mb-30" style="height: 200px;">
                <img class="img-fluid" src="{{ $setting->homepage_image_4 }}" alt="">
                <div class="offer-text">
                    {!! $setting->homepage_img4d !!}
                </div>
            </div>
            </div>
            @endif
        </div>
    </div>