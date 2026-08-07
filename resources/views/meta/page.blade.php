@php
    $site = $setting ?? $sett ?? \DB::table('setting')->where('id', 1)->first();
    $siteName = seo_site_name($site);
@endphp
@foreach (($pages ?? collect()) as $page)
@php
    $title = !empty($page->seo_title) ? $page->seo_title : (($page->name ?? 'Page') . ' | ' . $siteName);
    $description = $page->seo_description ?? '';
    $keywords = $page->seo_keywords ?? '';
    $canonical = seo_canonical(url('/' . ltrim((string) ($page->slug ?? ''), '/')));
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
<meta property="og:title" content="{{ !empty($page->seo_title) ? $page->seo_title : ($page->name ?? $title) }}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:url" content="{{ $canonical }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ $title }}" />
<meta name="twitter:description" content="{{ $description }}" />
@endforeach
