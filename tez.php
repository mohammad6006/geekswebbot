<?php
require "vendor/autoload.php";
use PHPHtmlParser\Dom;

$dom = new Dom;
$dom->load('<div class="all"><p>Hey bro, <a href="google.com">click here</a><br /> :)</p></div>');
$a = $dom->find('a')[0];
echo $a->text; // "click here"
