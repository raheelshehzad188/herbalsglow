@php
    $site = $setting ?? $sett ?? \DB::table('setting')->where('id', 1)->first();
    $siteName = seo_site_name($site);
    $productRow = $product[0] ?? $item ?? null;
    $title = !empty($meta->title) ? $meta->title : ($productRow->product_name ?? '');
    $description = !empty($meta->description) ? $meta->description : strip_tags((string) ($productRow->short_discriiption ?? ''));
    $keywords = !empty($meta->keywords) ? $meta->keywords : str_replace('-', ' ', (string) ($productRow->tags ?? ''));
    $canonical = seo_canonical(isset($meta->url) ? $meta->url : url('/product/' . ($productRow->slug ?? '')));
    $image = img_url($productRow->image_one ?? '');
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
<meta property="og:type" content="product" />
<meta property="og:site_name" content="{{ $siteName }}" />
<meta property="og:title" content="{{ $title }}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:url" content="{{ $canonical }}" />
<meta property="og:image" content="{{ $image }}" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $title }}" />
<meta name="twitter:description" content="{{ $description }}" />
<meta name="twitter:image" content="{{ $image }}" />
<meta property="product:price:amount" content="{{ number_format(cart_product_unit_price($productRow), 2, '.', '') }}" />
<meta property="product:price:currency" content="{{ env('CUR_CODE', 'PKR') }}" />
