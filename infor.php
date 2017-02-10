<?php

require 'vendor/autoload.php';
use PHPHtmlParser\Dom;
$dom = new Dom;
$dom->load('https://www.tv360.com.tr/BroadStream-Index');
$html = $dom->outerHtml;
$a = $dom->find('div[id=tab-5] div[class=programadi]');
$arri = "list";
foreach ($a as $key => $btime) {
    $arri .= $btime->text;
}
echo $arri;
    // return $arri .= "\n آدرس کانال : @TurkTv \n کانال ویدئویی: @canli \n ربات راهنما : @TurkTvBot";
    // $dom->load('https://www.tv360.com.tr/BroadStream-Index');
    // $html = $dom->outerHtml;
    // $roztime = $dom->find('span[class=date]')[0];
    // $btimes = $dom->find('div[class=two columns time]');
    // $progtitles = $dom->find('div[class=ten columns]');
    // $arri = "لیست برنامه های امروز {$kanal} \n زمان برنامه ها به وقت ایران میباشد \n";
    // foreach ($btimes as $key => $btime) {
    //     $arri .= zamanmahali($btime->text).":".$progtitles[$key]->text."\n";
    // }
    // return $arri .= "\n آدرس کانال : @TurkTv \n کانال ویدئویی: @canli \n ربات راهنما : @TurkTvBot";

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
