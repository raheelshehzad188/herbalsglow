@php
$bigcatdata = $category_id;
@endphp
<title>{{ isset($seo->title)?$seo->title:'' }}</title>
<meta name="description" content="<?php echo trim($seo->description);?>" />
<meta name="keywords" content="<?= $seo->keywords ?>" />
<link rel="canonical" href="<?php echo url('/category').'/'.$bigcatdata->slug;?>" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?php echo $seo->title;?>" />
<meta property="og:description" content="<?php echo trim($seo->description);?>" />
<meta property="og:url" content="<?php echo url('/category').'/'.$bigcatdata->slug;?>" />
<meta name="twitter:title" content="<?php echo $bigcatdata->name;?>">
<meta name="twitter:description" content="<?php echo trim($seo->description);?>">
<meta property="og:site_name" content="Telemart Pakistan" />