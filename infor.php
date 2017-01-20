<?php

require 'vendor/autoload.php';
use Larabros\Elogram\Client;
$clientId = 'a44d4e709d97471f9c8c5d3112d8d81e';
$clientSecret = '491b84904cac43529904997b2252ca1f';
$redirectUrl = 'https://turktv.herokuapp.com/infor.php';
$accessToken = {"user":{"profile_picture":"https:\/\/scontent.cdninstagram.com\/t51.2885-19\/s150x150\/12545285_1642118132719843_625718016_a.jpg","id":"2662699150","website":"https:\/\/tlgrm.me\/turktv","username":"farsi.survivor","bio":"\u06a9\u0627\u0645\u0644\u062a\u0631\u06cc\u0646 \u0648 \u0628\u0647 \u0631\u0648\u0632\u062a\u0631\u06cc\u0646 \u0635\u0641\u062d\u0647 \u0641\u0627\u0631\u0633\u06cc \u0633\u0648\u0631\u0648\u0627\u06cc\u0648\u0631 2017\n\u0628\u0631\u0646\u0627\u0645\u0647 \u0628\u0627\u0632\u06cc\u0647\u0627 \u062e\u0644\u0627\u0635\u0647 \u0628\u06cc\u0648\u06af\u0631\u0627\u0641\u06cc \u0646\u062a\u06cc\u062c\u0647 \u062c\u0627\u06cc\u0632\u0647 \u0647\u0627 \u06a9\u0644\u06cc\u067e \u0645\u0648\u0632\u06cc\u06a9 \u0648 \u062a\u0631\u062c\u0645\u0647\n\u062d\u062a\u0645\u0627 \u0627\u0632 \u06a9\u0627\u0646\u0627\u0644 \u062a\u0644\u06af\u0631\u0627\u0645\u0645\u0648\u0646 \u062f\u06cc\u062f\u0646 \u06a9\u0646\u06cc\u062f","full_name":"Survivor 2017 \u0633\u0648\u0631\u0648\u0627\u06cc\u0648\u0631 \u0641\u0627\u0631\u0633\u06cc"},"access_token":"2662699150.a44d4e7.f7484a3b890249e0bb71b84c5e31091b","resource_owner_id":"2662699150"};

$client = new Client($clientId, $clientSecret, $accessToken, $redirectUrl);

$response = $client->tags()->get('nofilter');
echo json_encode($response->get());

// // Start the session
// session_start();

// // If we don't have an authorization code then get one
// if (!isset($_GET['code'])) {
//     header('Location: ' . $client->getLoginUrl());
//     exit;
// } else {
//     $token = $client->getAccessToken($_GET['code']);
//     echo json_encode($token); // Save this for future use
// }

// // You can now make requests to the API
// $client->users()->search('skrawg');

// $instagram = new Instagram();
// $instagram->get('farsi.survivor');
// var_dump($instagram);
// $media = Bolandish\Instagram::getMediaByHashtag("benimkizim", 10);
// echo json_encode($media);
// foreach($media as $value){
//   if ($value->dimensions->width === $value->dimensions->height){
//         $url = trim(strtok($value->display_src, '?')); 
//         $response = $client->sendPhoto([
//             'chat_id'=> $update->callback_query->message->chat->id,
//             'photo'=>fopen($url,'r'),
//             'caption'=>"کانال: @TurkTv \n ربات : @TurkTvBot"
//             ]);
//   }


// $fekeransfile = file_get_contents("./survivor.json");
// $json_o=json_decode($fekeransfile);
// $btns = [];
//             foreach ($json_o->unluler as $value) {
//                     array_push($btns, [array("text"=>$value->name,"callback_data"=>$value->slug)]);
//                 }
// print_r($btns);
// require 'vendor/autoload.php';

// $token = getenv('acstok');
// $dbopts = parse_url(getenv('DATABASE_URL'));
// $dsn = 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"] . ';port=' . $dbopts["port"];
// $user = $dbopts["user"];
// $pw = $dbopts["pass"];
// $pdo = new PDO($dsn, $user, $pw);
// $fpdo = new FluentPDO($pdo);

// $query = $fpdo->from('messages');
// foreach ($query as $row) {
//     echo "$row[user_id] \n $row[chat_id] \n $row[message_id] \n $row[daryaft] \n $row[ersal] \n";
// }

// $query = $fpdo->from('messages')->where('user_id', 123);
// $values = array('user_id' => '123', 'chat_id' => '321', 'message_id' => '456', 'daryaft' => 'abc', 'ersal' => 'def');       
//     $query = $fpdo->insertInto('messages')->values($values);    
//     $insert = $query->execute();

    // var_dump($query);
    // will output:
    // string(1) "3"
