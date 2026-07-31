@php
$bigcatdata = $category_id;
@endphp
@if($seo)
    <title>{!! isset($seo->title) ? html_entity_decode($seo->title) : '' !!}</title>
    <meta name="description" content="{!! trim(html_entity_decode($seo->description)) !!}" />
    <meta name="keywords" content="{!! html_entity_decode($seo->keywords) !!}" />
    <link rel="canonical" href="{{ url('/') . '/category/' . $bigcatdata->slug }}" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{!! html_entity_decode($seo->title) !!}" />
    <meta property="og:description" content="{!! trim(html_entity_decode($seo->description)) !!}" />
    <meta property="og:url" content="{{ url('/') . '/category/' . $bigcatdata->slug }}" />
    <meta name="twitter:title" content="{!! $bigcatdata->name !!}">
    <meta name="twitter:description" content="{!! trim(html_entity_decode($seo->description)) !!}">
    <meta property="og:site_name" content="{{ $meta->title ?? 'Quicknow.pk' }}" />
@endif
