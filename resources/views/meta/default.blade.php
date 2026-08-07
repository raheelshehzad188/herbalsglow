@php
    $site = $Site ?? $setting ?? \DB::table('setting')->where('id', 1)->first();
    $siteName = seo_site_name($site);
    $title = $site->title ?? $site->site_title ?? $siteName;
    $description = $site->description ?? $site->seo_description ?? '';
    $keywords = $site->keywords ?? '';
    $canonical = seo_canonical(isset($meta->url) ? $meta->url : url('/'));
@endphp
<title>{{ $title }}</title>
<meta name="title" content="{{ $title }}" />
<meta name="description" content="{{ $description }}" />
<meta name="keywords" content="{{ $keywords }}" />
<meta name="robots" content="index,follow" />
<meta name="googlebot" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1" />
<meta name="bingbot" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1" />
<link rel="canonical" href="{{ $canonical }}" />
<link rel="alternate" type="application/rss+xml" href="{{ url('/sitemap.xml') }}" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="{{ $siteName }}" />
<meta property="og:title" content="{{ $title }}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:url" content="{{ $canonical }}" />
@if(!empty($site->logo) || !empty($site->logo1))
<meta property="og:image" content="{{ img_url($site->logo ?? $site->logo1) }}" />
@endif
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $title }}" />
<meta name="twitter:description" content="{{ $description }}" />
