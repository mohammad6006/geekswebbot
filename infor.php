<?php
require 'vendor/autoload.php';
use PHPHtmlParser\Dom;
$dom = new Dom;
function zamanmahali($zaman)
{
    $zaman1 = strtotime($zaman) + strtotime('00:30');
    return date('H:i',$zaman1);
}

    $dom->loadFromUrl('https://www.tvyayinakisi.com/kanal-d-tv');
    $html = $dom->outerHtml;

    $bnames = $dom->find('div[class=active] ul li');
    echo $bnames;
    // $btimes = $dom->find('div[class=active] ul li p[class="time"]');
    // echo $btimes;
    foreach ($bnames as $key => $value) {
        echo zamanmahali($value->{'data-start'});
        echo $value->find('p[class="name"]')->text;
        echo "<br>";
    }

    // echo count($btimes);
    // $roztime = $dom->find('span[class=date]')[0];
    // $btimes = $dom->find('div[class=two columns time]');
    // $progtitles = $dom->find('div[class=ten columns]');
    // $arri = "سسس";
    // foreach ($btimes as $key => $btime) {
    //     $arri .= ($btime->text).":".$progtitles[$key]->text."\n";
    // }
    // return $arri .= "\n آدرس کانال : @TurkTv \n کانال ویدئویی: @canli \n ربات راهنما : @TurkTvBot";

// $dom->load('https://video.acunn.com/survivor');
// $html = $dom->outerHtml;
// $a = $dom->find('div[class=videos-list] div[class=row] div div[class=list-type-one-content] a');
// function get_string_between($string, $start, $end){
//     $string = ' ' . $string;
//     $ini = strpos($string, $start);
//     if ($ini == 0) return '';
//     $ini += strlen($start);
//     $len = strpos($string, $end, $ini) - $ini;
//     return substr($string, $ini, $len);
// }
// // echo count($a);
// $tmp = 0;
// foreach ($a as $key => $value) {
//     if ($tmp < 12) {
//         echo $value->getAttribute('title');
//         echo "\n";
//         $url = $value->getAttribute('href');
//         $data = file_get_contents( $url );
//         $parsed = get_string_between($data, 'https://video-cdn.acunn.com', '-480p.mp4');
//         echo 'https://video-cdn.acunn.com'.$parsed.'-480p.mp4';
//         echo "\n";
//     }
//     $tmp++;
// }
// echo $a->text;
// return 'asal';
// print_r($a);


// $data = file_get_contents('https://www.w3schools.com/w3css/tryw3css_templates_band.htm');

// require 'vendor/autoload.php';
// use PHPHtmlParser\Dom;
// $dom = new Dom;
// $dom->load('https://www.w3schools.com/w3css/tryw3css_templates_band.htm');
// $html = $dom->outerHtml;
// var_dump($html);
// $a = $dom->find('div[class=w3-row-padding w3-padding-32] div img');
// echo count($a);
// foreach ($a as $key => $value) {
//     echo $value->getAttribute('src');
// }
// echo $a->text;
// return 'asal';
// print_r($a);
// $arri = "list";


// $match = '';



// foreach ($a as $key => $btime) {
//     $arri .= $btime->text;
// }
// echo $arri;

// <hr>
// print_r($a);
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
