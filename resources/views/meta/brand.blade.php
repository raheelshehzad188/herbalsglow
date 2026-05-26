 
@php
$setting = DB::table('setting')->first();
$site_name = $setting->site_title ?? 'Shop Pakistan';
@endphp
 <title><?php echo $brand->title ?> - {{ $site_name }}</title>
    <meta name="description" content="{{ $brand->description }}">
    <meta property="keywords" content="<?php echo $brand->s_keywords;?>">
   <meta property="og:site_name" content="{{ $site_name }}">
<meta property="og:title" content="<?php echo $brand->title;?>">

<meta property="og:description" content="{{ $brand->description }}">

<meta property="og:url" content="{{ url('brand/'.$brand->slug); }}">
<link rel="canonical" href="{{ url('brand/'.$brand->slug); }}" />
<meta property="og:type" content="Ecommerce Website">
