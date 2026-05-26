<!-- Icon box -->
        <section class="flat-spacing-1 flat-iconbox">
            <div class="container">
                <div class="wrap-carousel wrap-mobile wow fadeInUp" data-wow-delay="0s">
                    <div class="swiper tf-sw-mobile" data-preview="1" data-space="15">
                        <div class="swiper-wrapper wrap-iconbox">
                            @foreach($boxes as $k=> $v)
                            <div class="swiper-slide">
                                <div class="tf-icon-box style-row">
                                    <div class="icon">
                                        <i class="icon-{{$v->icon}}"></i>
                                    </div>
                                    <div class="content">
                                        <div class="title fw-4">{{$v->heading}}</div>
                                        <p>{{$v->txt}}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
    
                    </div>
                    <div class="sw-dots style-2 sw-pagination-mb justify-content-center"></div>
                </div>
            </div>
        </section>
        <!-- /Icon box -->