<?php
$xmlString = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
$xmlString .='<url>';
$xmlString .='<loc>' .url('').'</loc>';
$xmlString .='<priority>1.0</priority>';
$xmlString .='<changefreq>daily</changefreq>';
$xmlString .='</url>';



foreach($products as $k =>$pd) {
    $xmlString .='<url>';
    $xmlString .='<loc>'.url($pd->slug).'</loc>';
    $xmlString .='<priority>1.0</priority>';
    $xmlString .='<changefreq>daily</changefreq>';
    $xmlString .='</url>\n';
}

$xmlString .='</urlset>';

$myfile = fopen($_SERVER["DOCUMENT_ROOT"].'/public/sitemap/sitemap.xml', "w") or die("Unable to open file!");
fwrite($myfile, $xmlString);
fclose($myfile);
if($myfile){
    echo "<h2>Site Map Created SuccessFully</h2>";
}else{
    echo "<h2>Site Map Created Failed</h2>";
};
?>