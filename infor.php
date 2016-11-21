<?php
require 'vendor/autoload.php';

$token = getenv('acstok');
$dbopts = parse_url(getenv('DATABASE_URL'));

$pdo = new PDO('pgsql:dbname='.ltrim($dbopts["path"],'/').', '.$dbopts["user"].', '.$dbopts["pass"]);
$fpdo = new FluentPDO($pdo);
