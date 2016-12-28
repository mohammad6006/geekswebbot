<?php
require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', 'uA85crJv3zgAICaIcK9Cj4U5m');
define('CONSUMER_SECRET', 'yIniiFc0Vq9qhXyswVP0iNeqAM9CVyRpDmM3yYJNvxfD48NBgA');
define('ACCESS_TOKEN', '88899173-OMipak5KjBUXQu8i49G7dxOWt12Lv68bE8yccnGAn');
define('ACCESS_TOKEN_SECRET', 'qbLTgO0dNs4Eixqzl983qbLf75UQgc5UK4tBbwqsQKpFv');
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
$content = $connection->get("account/verify_credentials");
print_r($content);