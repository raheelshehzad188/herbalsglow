@php
    $site = $setting ?? $sett ?? \DB::table('setting')->where('id', 1)->first();
    $siteName = seo_site_name($site);
    $tagLabel = $btags ?? 'Brand Tag';
    $title = $tagLabel . ' - ' . $siteName;
    $first = $product[0] ?? null;
    $rawDesc = isset($first->short_discriiption) ? strip_tags((string) $first->short_discriiption) : $tagLabel;
    $description = strlen($rawDesc) > 160 ? substr($rawDesc, 0, 157) . '...' : $rawDesc;
    $keywords = $brands[0]->keywords ?? $tagLabel;
    $canonical = seo_canonical(url()->current());
    $image = $first ? img_url($first->image_one ?? '') : '';
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
<meta property="og:title" content="{{ $title }}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:url" content="{{ $canonical }}" />
@if($image)
<meta property="og:image" content="{{ $image }}" />
@endif
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ $title }}" />
<meta name="twitter:description" content="{{ $description }}" />
