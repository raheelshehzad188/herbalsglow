<?php
$xmlString = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
$xmlString .='<url>';
$xmlString .='<loc>' .url('').'</loc>';
$xmlString .='<priority>1.0</priority>';
$xmlString .='<changefreq>daily</changefreq>';
$xmlString .='</url>';




foreach($mcat as $mcatdata) {
    $xmlString .='<url>';
    $xmlString .='<loc>'.url($mcatdata->slug).'</loc>';
    $xmlString .='<priority>1.0</priority>';
    $xmlString .='<changefreq>daily</changefreq>';
    $xmlString .='</url>';
}


$xmlString .='</urlset>';
$dom = new DOMDocument;
$dom->preserveWhiteSpace = TRUE;
$dom->loadXML($xmlString);
if($dom->save($_SERVER["DOCUMENT_ROOT"].'/public/sitemap/maincategorysitemap.xml')){
    echo "<h2>Site Map Created SuccessFully</h2>";
}else{
    echo "<h2>Site Map Created Failed</h2>";
};
?>