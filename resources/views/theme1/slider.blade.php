
<div class="tf-slideshow slider-effect-fade slider-skincare position-relative">
            <div class="swiper tf-sw-slideshow" data-preview="1" data-tablet="1" data-mobile="1" data-centered="false" data-space="0" data-loop="true" data-auto-play="false" data-delay="2000" data-speed="1000">
                <div class="swiper-wrapper">
                    @foreach ($Slider as $key => $slide)
                        <div class="swiper-slide" lazy="true">
                        <div class="wrap-slider">
                            <img class="lazyload" data-src="{{ env('IMG_URL'); }}public/img/slider/{{$slide->slider_image}}" src="{{env('IMG_URL')}}public/img/slider/{{$slide->slider_image}}" alt="skincare-slideshow-01" loading="lazy">
                            <div class="box-content text-start">
                                <div class="new" style="margin-left:100px; width:54%;">
                                    <h3 style="@media (max-width:425px){ display:none; }">{!! strip_tags($slide->p)!!}</h3>
                                    @if($slide->heading)
                                    <a href="{{$slide->button}}" style="margin-top:50px;" class="fade-item fade-item-3 tf-btn btn-light-icon animate-hover-btn btn-xl radius-3"><span>{{$slide->heading}}</span><i class="icon icon-arrow-right"></i></a> 
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="wrap-pagination sw-absolute-3">
                <div class="sw-dots style-2 dots-white sw-pagination-slider justify-content-center"></div>
            </div>
        </div>