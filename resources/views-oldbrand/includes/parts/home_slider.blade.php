
<!-- Carousel Start -->
<div class="container-fluid mb-3">
    <div class="row px-xl-5">
        <div class="col-lg-8">
            <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach ($Slider as $key => $slide)
                        <li data-target="#header-carousel" data-slide-to="{{ $key }}" class="{{ (!$key)?'active':'' }}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach ($Slider as $key => $slide)
                        <div class="carousel-item position-relative {{ (!$key)?'active':'';  }}" style="height: 430px;">
                            <img class="position-absolute w-100 h-100" src="{{asset('')}}public/img/slider/{{$slide->slider_image}}" style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                {!! $slide->p!!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @if(isset($setting->homepage_image_one) && $setting->homepage_image_one && file_exists($setting->homepage_image_one))
            <div class="product-offer mb-30" style="height: 200px;">
                <img class="img-fluid" src="{{ $setting->homepage_image_one }}" alt="">
                <div class="offer-text">
                    {!! $setting->homepage_img1d !!}
                </div>
            </div>
            @endif
            @if(isset($setting->homepage_image_two) && $setting->homepage_image_two && file_exists($setting->homepage_image_two))
            <div class="product-offer mb-30" style="height: 200px;">
                <img class="img-fluid" src="{{ $setting->homepage_image_two }}" alt="">
                <div class="offer-text">
                    {!! $setting->homepage_img2d !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<!-- Carousel End -->