<?php
$all = array();
foreach($products as $getbrandtag){
    $brand_tag_slug = $getbrandtag->slug;
    if($brand_tag_slug)
    {
          $all = array_merge($all, explode(',', $brand_tag_slug));

        
    }
    
}
$xmlString = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
$xmlString .='<url>';
$xmlString .='<loc>' .url('').'</loc>';
$xmlString .='<priority>1.0</priority>';
$xmlString .='<changefreq>daily</changefreq>';
$xmlString .='</url>';
foreach($all as $k=> $tag)
{
        if($tag){
                $ovbrand_tag_slug = preg_replace('/[^a-z0-9]+/i','-',$tag);
                $xmlString .='<url>';
                $xmlString .='<loc>'.url('brand-tags/'.$ovbrand_tag_slug).'</loc>';
                $xmlString .='<priority>0.9</priority>';
                $xmlString .='<changefreq>daily</changefreq>';
                $xmlString .='</url>';
        }

}

$xmlString .='</urlset>';
$dom = new DOMDocument;
$dom->preserveWhiteSpace = TRUE;
$dom->loadXML($xmlString);
$file = '/public/sitemap/'.$fname;
echo $file.'<br>';
if($dom->save($_SERVER["DOCUMENT_ROOT"].$file)){
    echo "<h2> ".$file." Site Map Created SuccessFully</h2>";
}else{
    echo "<h2>Site Map Created Failed</h2>";
}
echo 'I m here';

?>