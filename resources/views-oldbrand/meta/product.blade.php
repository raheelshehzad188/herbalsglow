<title>{{ isset($meta->title)?$meta->title:'' }}</title>
    <meta name="description" content="{{ (isset($meta->description)?$meta->description:'') }}" />
    <meta name="keywords" content="{{ isset($meta->keywords)?$meta->keywords:'' }}" />
<link rel="canonical" href="{{url('/product/').'/'.$product[0]->slug}}" />  
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="Product" />
<meta property="og:title" content="{{ isset($meta->title)?$meta->title:'' }}" />
<meta property="og:description" content="{{ (isset($meta->description)?$meta->description:'') }}" />
<meta property="og:url" content="{{url('/product/').'/'.$product[0]->slug}}" />
<meta property="og:site_name" content="Telemart Pakistan" />
<meta property="og:image" content="{{ url($product[0]->image_one) }}" />