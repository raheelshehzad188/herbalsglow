@php
$setting = DB::table('setting')->first();
$site_name = $setting->site_title ?? 'Shop Pakistan';
$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
@endphp
<title>{{ $btags }} 03008856924</title>
    <meta name="description" content="{{ isset($product[0]->short_discriiption) ? (strlen($product[0]->short_discriiption) > 170 ? substr($product[0]->short_discriiption, 0, 140) : $product[0]->short_discriiption) : ''}}" />
    <meta property="keywords" content="<?php echo $brands[0]->keywords;?>">

<meta property="og:site_name" content="{{ $site_name }}" />
<link rel="canonical" href="{{ $actual_link; }}" />
<meta property="og:image" content="{{ url($product[0]->image_one) }}" />