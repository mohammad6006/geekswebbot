<?php
require 'vendor/autoload.php';

$token = getenv('acstok');
$dbopts = parse_url(getenv('DATABASE_URL'));
$dsn = 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"] . ';port=' . $dbopts["port"];
$user = $dbopts["user"];
$pw = $dbopts["pass"];
$pdo = new PDO($dsn, $user, $pw);
$fpdo = new FluentPDO($pdo);
