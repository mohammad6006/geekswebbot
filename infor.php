<?php

require 'vendor/autoload.php';
use Vinkla\Instagram\Instagram;

$instagram = new Instagram();
$instagram->get('farsi.survivor');
var_dump($instagram);
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
