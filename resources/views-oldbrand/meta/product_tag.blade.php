<title>{{ isset($tags)?$tags:'' }}</title>
    <meta name="description" content="{{ (isset($product[0]->short_discriiption)?$product[0]->short_discriiption:'') }}" />
    <meta name="keywords" content="{{ (isset($product[0]->tags)?$product[0]->tags:'') }}" />
<link rel="canonical" href="{{url('/product/').'/'.$product[0]->slug}}" />  
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="Product" />
<meta property="og:title" content="{{ isset($tags)?$tags:'' }}" />
<meta property="og:description" content="{{ (isset($meta->description)?$meta->description:'') }}" />
<meta property="og:url" content="{{url('/product/').'/'.$product[0]->slug}}" />
<meta property="og:site_name" content="Telemart Pakistan" />
<meta property="og:image" content="{{ url($product[0]->image_one) }}" />