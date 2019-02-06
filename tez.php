<?php
require "vendor/autoload.php";
use PHPHtmlParser\Dom;

$dom = new Dom;
$dom->load('https://video.acunn.com/survivor');
$html = $dom->outerHtml;
$a = $dom->find('div[class=videos-list] div[class=row] div div[class=list-type-one-content] a');
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
// echo count($a);
$tmp = 0;
foreach ($a as $key => $value) {
    if ($tmp < 12) {
        echo $value->getAttribute('title');
        echo "\n";
        $url = $value->getAttribute('href');
        $data = file_get_contents( $url );
        $parsed = get_string_between($data, 'https://video-cdn.acunn.com', '-480p.mp4');
        echo 'https://video-cdn.acunn.com'.$parsed.'-480p.mp4';
        echo "<br>";
    }
    $tmp++;
}
echo $a->text;
return 'asal';
print_r($a);