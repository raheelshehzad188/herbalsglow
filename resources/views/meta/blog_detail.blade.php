@php
    $site = $setting ?? $sett ?? \DB::table('setting')->where('id', 1)->first();
    $siteName = seo_site_name($site);
    $post = $blogs_detail ?? null;
    $title = $post->title ?? 'Blog';
    $description = $post->description ?? '';
    $keywords = $post->keywords ?? '';
    $canonical = seo_canonical(url('/blog/' . ($post->slug ?? '')));
@endphp
<title>{{ $title }}</title>
<meta name="title" content="{{ $title }}" />
<meta name="description" content="{{ $description }}" />
<meta name="keywords" content="{{ $keywords }}" />
<meta name="robots" content="index,follow" />
<link rel="canonical" href="{{ $canonical }}" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="article" />
<meta property="og:site_name" content="{{ $siteName }}" />
<meta property="og:title" content="{{ $title }}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:url" content="{{ $canonical }}" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $title }}" />
<meta name="twitter:description" content="{{ $description }}" />
