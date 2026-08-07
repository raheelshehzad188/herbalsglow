{{-- Theme3 SEO head: same meta files + schema as theme2/layout --}}
@php
    use App\Models\Admins\Setting;
    $Site = $Site ?? $setting ?? Setting::where('id', 1)->first();
@endphp

@if(isset($meta_file) && $meta_file)
    @include($meta_file)
@else
    @include('meta.default')
@endif

@if(isset($meta) && $meta)
    @if(!empty($meta->scheme1))
        <script type="application/ld+json">{!! is_string($meta->scheme1) ? $meta->scheme1 : json_encode($meta->scheme1, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    @if(!empty($meta->scheme))
        <script type="application/ld+json">@json($meta->scheme)</script>
    @endif
@elseif(!(isset($meta_file) && in_array($meta_file, ['meta.brand', 'meta.product_tag', 'meta.brand_tag', 'meta.page', 'meta.blog_detail', 'meta.product', 'meta.categoy'], true)))
    @if(Session::has('title'))
        <title>{{ Session::get('title') }} | {{ $Site->site_title ?? seo_site_name($Site) }}</title>
    @else
        <title>{{ $Site->site_title ?? seo_site_name($Site) }}</title>
    @endif
@endif

@if(empty($meta->scheme ?? null) && empty($meta->scheme1 ?? null) && (!isset($meta_file) || $meta_file === 'meta.default'))
    <script type="application/ld+json">@json(seo_organization_schema($Site))</script>
    <script type="application/ld+json">@json(seo_website_schema($Site))</script>
@endif
