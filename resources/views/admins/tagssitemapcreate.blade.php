<?php
$total = 0;
foreach($products as $gettag){
    $tag = $gettag->tags;
    if($tag != ''){
        $tagsi = explode(',', $tag);
        if($tagsi != ''){
            
            $big_boy[] = array_merge($tagsi);
           $total = $total + count($tagsi);
        }
    }

}


$result = array();
foreach ($big_boy as $key => $value) {
    if(is_array($value)){
        $result = array_merge($result, $value);
    }
}
 
 list($tagmap, $tagmap1) = array_chunk($result, ceil(count($result) / 2));
$xmlString = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
$xmlString .='<url>';
$xmlString .='<loc>' .url('').'</loc>';
$xmlString .='<priority>1.0</priority>';
$xmlString .='<changefreq>daily</changefreq>';
$xmlString .='</url>';


            foreach($tagmap as $oki){
                $ov = preg_replace('/[^a-z0-9]+/i','-',$oki);
                $xmlString .='<url>';
                $xmlString .='<loc>'.url('tags/'.$ov).'</loc>';
                $xmlString .='<priority>0.8</priority>';
                $xmlString .='<changefreq>daily</changefreq>';
                $xmlString .='</url>';
                
            }
           




$xmlString .='</urlset>';
$dom = new DOMDocument;
$dom->preserveWhiteSpace = TRUE;
$dom->loadXML($xmlString);
if($dom->save($_SERVER["DOCUMENT_ROOT"].'/public/sitemap/tagssitemap.xml')){
    echo "<h2>Site Map Created SuccessFully</h2>";
}else{
    echo "<h2>Site Map Created Failed</h2>";
};



$xmlString1 = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach($tagmap1 as $oki1){
                $ov1 = preg_replace('/[^a-z0-9]+/i','-',$oki1);
                $xmlString1 .='<url>';
                $xmlString1 .='<loc>'.url('tags/'.$ov1).'</loc>';
                $xmlString1 .='<priority>0.8</priority>';
                $xmlString1 .='<changefreq>daily</changefreq>';
                $xmlString1 .='</url>';
                
            }
           




$xmlString1 .='</urlset>';
$dom1 = new DOMDocument;
$dom1->preserveWhiteSpace = TRUE;
$dom1->loadXML($xmlString1);
if($dom1->save($_SERVER["DOCUMENT_ROOT"].'/public/sitemap/newsitemap.xml')){
    echo "<h2>Site Map Created SuccessFully</h2>";
}else{
    echo "<h2>Site Map Created Failed</h2>";
};


    

?>