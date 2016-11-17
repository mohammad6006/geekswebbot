<?php
require 'vendor/autoload.php';

$token = getenv('acstok');
$client = Zelenin\Telegram\Bot\ApiFactory::create($token);
$update = json_decode(file_get_contents('php://input'));


try {
    var_dump($update);
    
} catch (Zelenin\Telegram\Bot\Exception\NotOkException $e) {
    echo $e->getMessage();
}