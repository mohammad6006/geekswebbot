<?php
require "vendor/autoload.php";
use PHPHtmlParser\Dom;

$dom = new Dom;
    $dom->loadFromUrl('http://www.tvyayinakisi.com/kanal-d-tv');
    $html = $dom->outerHtml;
    $bnames = $dom->find('div[class=site-logo]');
echo count($bnames);
