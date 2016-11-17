<?php
require 'vendor/autoload.php';

$token = getenv('acstok');
$client = Zelenin\Telegram\Bot\ApiFactory::create($token);
$update = json_decode(file_get_contents('php://input'));
echo "string";
var_dump($client)
var_dump($update)