@php
    $site = $setting ?? $sett ?? \DB::table('setting')->where('id', 1)->first();
    $siteName = seo_site_name($site);
    $brandRow = $brand ?? null;
    $title = trim(($brandRow->title ?? $brandRow->name ?? 'Brand') . ' - ' . $siteName);
    $description = $brandRow->description ?? ($brandRow->name ?? '');
    $keywords = $brandRow->s_keywords ?? $brandRow->keywords ?? ($brandRow->name ?? '');
    $canonical = seo_canonical(url('brand/' . ($brandRow->slug ?? '')));
@endphp
<title>{{ $title }}</title>
<meta name="title" content="{{ $title }}" />
<meta name="description" content="{{ $description }}" />
<meta name="keywords" content="{{ $keywords }}" />
<meta name="robots" content="index,follow" />
<link rel="canonical" href="{{ $canonical }}" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="{{ $siteName }}" />
<meta property="og:title" content="{{ $brandRow->title ?? $brandRow->name ?? $title }}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:url" content="{{ $canonical }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ $title }}" />
<meta name="twitter:description" content="{{ $description }}" />
