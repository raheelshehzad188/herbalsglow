
@php
$setting = DB::table('setting')->first();
$site_name = $setting->site_title ?? 'Shop Pakistan';
@endphp
@foreach ($pages as $page) 
 <title><?php echo !empty($page->seo_title) ? $page->seo_title : ($page->name . ' | ' . $site_name);?></title>
   
    <meta name="description" content="<?php echo !empty($page->seo_description) ? $page->seo_description : '';?>">
    <meta name="keywords" content="<?php echo !empty($page->seo_keywords) ? $page->seo_keywords : '';?>">
<meta property="og:site_name" content="{{ $site_name }}" />
<meta property="og:title" content="<?php echo !empty($page->seo_title) ? $page->seo_title : $page->name;?>">

<meta property="og:description" content="<?php echo !empty($page->seo_description) ? $page->seo_description : '';?>">

<meta property="og:url" content="{{ url('/'.$page->slug); }}">
<link rel="canonical" href="{{ url('/'.$page->slug); }}" />
<meta property="og:type" content="Ecommerce Website">

 @endforeach

