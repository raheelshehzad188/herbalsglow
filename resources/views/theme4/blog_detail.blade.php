@extends($layout)
@section('content')
@php
    $v = $blogs_detail;
    $title = $v->title ?? $v->title_english ?? 'Blog';
    $body = $v->content ?? $v->description_english ?? '';
    $img = storefront_img($v->image ?? '', '/theme4/assets/images/homepage-one/about/blog-img-1.webp');
@endphp
<section class="blog about-blog">
    <div class="container">
        <div class="blog-bradcrum">
            <span><a href="{{ url('/') }}">Home</a></span>
            <span class="devider">/</span>
            <span><a href="{{ url('/blog') }}">Blogs</a></span>
            <span class="devider">/</span>
            <span><a href="{{ url('/blog/' . $v->slug) }}">{{ $title }}</a></span>
        </div>
        <div class="blog-heading about-heading">
            <h1 class="heading">{{ $title }}</h1>
        </div>
    </div>
</section>

<section class="blog-details product footer-padding">
    <div class="container">
        <div class="blog-detail-section">
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="blogs-wrapper">
                        <div class="wrapper-img">
                            <img src="{{ $img }}" alt="{{ $title }}">
                        </div>
                        <div class="wrapper-info">
                            @include('theme4.partials.blog_meta')
                            <h2 class="about-details wrapper-details blog-details-heading">{{ $title }}</h2>
                            <div class="blog-details">
                                {!! $body !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-post-section">
                        <h4 class="heading">Recent Posts</h4>
                        @foreach($rblog as $p)
                            @php
                                $pt = $p->title ?? $p->title_english ?? 'Blog';
                                $pi = storefront_img($p->image ?? '', '/theme4/assets/images/homepage-one/about/blog-img-2.webp');
                            @endphp
                            <div class="blog-post">
                                <div class="blogs-wrapper">
                                    <div class="wrapper-img">
                                        <a href="{{ url('/blog/' . $p->slug) }}"><img src="{{ $pi }}" alt="{{ $pt }}"></a>
                                    </div>
                                    <div class="wrapper-info">
                                        <a href="{{ url('/blog/' . $p->slug) }}" class="about-details wrapper-details">{{ $pt }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
