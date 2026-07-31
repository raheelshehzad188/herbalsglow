@if(isset($Slider) && count($Slider) > 0)
@php $sliderCount = count($Slider); @endphp
<div class="hp-bento-banners" data-banner-count="{{ $sliderCount }}">
    <div class="hp-bento-container">
        <div class="hp-bento-clip">
            <ul class="hp-bento-grid">
                @foreach($Slider as $index => $slide)
                @php
                    $img = custom_assets('public/img/slider/' . $slide->slider_image);
                    $link = !empty($slide->button) ? $slide->button : url('/shop');
                    $isHero = $index === 0;
                @endphp
                <li class="hp-bento-slot {{ $isHero ? 'hp-bento-slot-hero' : 'hp-bento-slot-tile' }} hp-bento-banner hp-bento-banner-{{ $index }}"
                    style="--banner-bg: url('{{ $img }}');">
                    <div class="cms-banner-wrapper cms-text-banner-wrapper">
                        <a href="{{ $link }}" class="cms-banner-wrapper-root-link">
                            <div class="cms-text-banner">
                                <div class="cms-banner-scaled-wrapper">
                                    <div class="cms-banner-scaled">
                                        <div class="cms-banner-container">
                                            <div class="cms-banner-wrapper-root t3-bento-bg">
                                                <div class="custom-flex-box t3-bento-text">
                                                    @if(!empty($slide->heading))
                                                    <div class="custom-flex-box-item">
                                                        <span class="t3-bento-title">{{ $slide->heading }}</span>
                                                    </div>
                                                    @endif
                                                    @if(!empty($slide->p))
                                                    <div class="custom-flex-box-item">
                                                        <span class="t3-bento-sub">{!! $slide->p !!}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif
