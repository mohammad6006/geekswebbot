<?php
require 'vendor/autoload.php';

$token = getenv('acstok');
$dbopts = parse_url(getenv('DATABASE_URL'));
echo $dbopts["user"];