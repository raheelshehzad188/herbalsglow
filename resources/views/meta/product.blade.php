<title>{{ isset($meta->title) && $meta->title ? $meta->title : ($product[0]->product_name ?? '') }}</title>
<meta name="description" content="{{ isset($meta->description) && $meta->description ? $meta->description : strip_tags($product[0]->short_discriiption ?? '') }}" />
<meta name="keywords" content="{{ isset($meta->keywords) && $meta->keywords ? $meta->keywords : ($product[0]->tags ?? '') }}" />
<link rel="canonical" href="{{ url('/product/' . $product[0]->slug) }}" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="product" />
<meta property="og:title" content="{{ isset($meta->title) && $meta->title ? $meta->title : ($product[0]->product_name ?? '') }}" />
<meta property="og:description" content="{{ isset($meta->description) && $meta->description ? $meta->description : strip_tags($product[0]->short_discriiption ?? '') }}" />
<meta property="og:url" content="{{ url('/product/' . $product[0]->slug) }}" />
<meta property="og:site_name" content="{{ $setting->site_title ?? ($setting->site_name ?? 'Store') }}" />
<meta property="og:image" content="{{ img_url($product[0]->image_one ?? '') }}" />
