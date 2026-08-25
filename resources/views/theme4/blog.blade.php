@extends($layout)
@section('content')
@php
    $fallback = '/theme4/assets/images/homepage-one/about/blog-img-1.webp';
@endphp
<section class="blog about-blog">
    <div class="container">
        <div class="blog-bradcrum">
            <span><a href="{{ url('/') }}">Home</a></span>
            <span class="devider">/</span>
            <span><a href="{{ url('/blog') }}">Blogs</a></span>
        </div>
        <div class="blog-heading about-heading">
            <h1 class="heading">Our Blogs</h1>
        </div>
    </div>
</section>

<section class="latest product footer-padding">
    <div class="container">
        <div class="blog-section latest-section">
            <div class="row g-5">
                @forelse($post as $v)
                    @php
                        $title = $v->title ?? $v->title_english ?? 'Blog';
                        $img = storefront_img($v->image ?? '', $fallback);
                    @endphp
                    <div class="col-lg-4 col-sm-6">
                        <div class="blogs-wrapper product-wrapper" data-aos="fade-up">
                            <div class="wrapper-img">
                                <a href="{{ url('/blog/' . $v->slug) }}"><img src="{{ $img }}" alt="{{ $title }}"></a>
                            </div>
                            <div class="wrapper-info">
                                @include('theme4.partials.blog_meta')
                                <a href="{{ url('/blog/' . $v->slug) }}" class="about-details wrapper-details">{{ $title }}</a>
                                <div class="divider"></div>
                                <a href="{{ url('/blog/' . $v->slug) }}" class="shop-btn">
                                    Learn More
                                    <span>
                                        <svg width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.6227 4.38176C12.5587 4.38176 12.4989 4.38176 12.4349 4.38176C8.56302 4.38176 4.69114 4.38176 0.819254 4.38176C0.7168 4.38176 0.614347 4.37785 0.516163 4.40129C0.195996 4.4677 -0.0302552 4.76459 0.00389589 5.05758C0.0423159 5.37791 0.302718 5.60839 0.644229 5.62793C0.712532 5.63183 0.780834 5.63183 0.853405 5.63183C4.71248 5.63183 8.57583 5.63183 12.4349 5.63183C12.4989 5.63183 12.5587 5.63183 12.6654 5.63183C12.5971 5.69824 12.5587 5.73731 12.516 5.77637C11.3805 6.8194 10.2407 7.86243 9.10517 8.90546C8.82342 9.16329 8.79354 9.51878 9.0326 9.77661C9.27166 10.0383 9.68574 10.0774 9.98029 9.86646C10.0272 9.8352 10.0657 9.79614 10.1084 9.75707C11.6494 8.34684 13.1905 6.93269 14.7273 5.51855C15.0987 5.17868 15.0987 4.83882 14.7273 4.49895C13.1777 3.077 11.6238 1.65504 10.0742 0.229172C9.8693 0.0416615 9.63878 -0.0481874 9.35276 0.0260357C8.88319 0.147137 8.70389 0.670605 9.00698 1.01437C9.0454 1.06125 9.09236 1.10032 9.13932 1.14329C10.2663 2.1746 11.389 3.20982 12.5203 4.24113C12.563 4.28019 12.6185 4.29972 12.6654 4.33098C12.6483 4.34269 12.6355 4.36223 12.6227 4.38176Z" fill="#AE1C9A"/></svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><p>No blog posts yet.</p></div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection
