@php
    $site = $setting ?? $sett ?? \DB::table('setting')->where('id', 1)->first();
    $siteName = seo_site_name($site);
    $cat = $category_id ?? $category ?? null;
    $seoRow = (is_object($seo ?? null) && !empty($seo->title ?? null)) ? $seo : $meta;
    $title = !empty($seoRow->title) ? html_entity_decode($seoRow->title) : ($cat->name ?? 'Category');
    $description = !empty($seoRow->description) ? trim(html_entity_decode($seoRow->description)) : ($cat->name ?? '');
    $keywords = !empty($seoRow->keywords) ? html_entity_decode($seoRow->keywords) : ($cat->name ?? '');
    $slug = $cat->slug ?? '';
    $canonical = seo_canonical(isset($meta->url) ? $meta->url : url('/category/' . $slug));
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
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ $title }}" />
<meta name="twitter:description" content="{{ $description }}" />
