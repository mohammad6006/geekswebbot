<?php

/*
* This file is part of GeeksWeb Bot (GWB).
*
* GeeksWeb Bot (GWB) is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License version 3
* as published by the Free Software Foundation.
* 
* GeeksWeb Bot (GWB) is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.  <http://www.gnu.org/licenses/>
*
* Author(s):
*
* © 2015 Kasra Madadipouya <kasra@madadipouya.com>
*
*/
require 'InstagramDownload.class.php';
require 'vendor/autoload.php';
use PHPHtmlParser\Dom;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$token = getenv('acstok');
$dbopts = parse_url(getenv('DATABASE_URL'));
$client = Zelenin\Telegram\Bot\ApiFactory::create($token);
$update = json_decode(file_get_contents('php://input'));
$dsn = 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"] . ';port=' . $dbopts["port"];
$user = $dbopts["user"];
$pw = $dbopts["pass"];
$pdo = new PDO($dsn, $user, $pw);
$fpdo = new FluentPDO($pdo);
$logger = new Logger('my_logger');
$logger->pushHandler(new StreamHandler(__DIR__.'/testlog1.log', Logger::DEBUG));

function zamanmahali($zaman)
{
    $zaman1 = strtotime($zaman) + strtotime('00:30');
    return date('H:i',$zaman1);
}

function listbarnameha($kanal)
{
    $dom = new Dom;
    $dom->load('http://www.tvyayinakisi.com/'.$kanal);
    $html = $dom->outerHtml;
    $roztime = $dom->find('span[class=date]')[0];
    $btimes = $dom->find('div[class=two columns time]');
    $progtitles = $dom->find('div[class=ten columns]');
    $arri = "لیست برنامه های امروز {$kanal} \n زمان برنامه ها به وقت ایران میباشد \n";
    foreach ($btimes as $key => $btime) {
        $arri .= zamanmahali($btime->text).":".$progtitles[$key]->text."\n";
    }
    return $arri .= "\n آدرس کانال : @TurkTv \n ربات : @TurkTvBot";
}
function tezfanc($taz)
{
    try {
        $taza = 'tazaaaa';
    } catch (Exception $e) {
        $taza = '1111';
    }
    return $taza;
}
function simplemessage($chatid,$text)
{
    global $client;
    try {
        $response = $client->sendChatAction(['chat_id' => $chatid, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $chatid,
            'text' => $text,
        ]); 
    return $response;       
    } catch (Exception $e) {
    }
}
try {
    if(isset($update->inline_query))
    {
        $logger->addInfo('inline_query - chatid:'.$update->inline_query->id.'-'.$update->inline_query->query);
        // $response = $client->answerInlineQuery([
        //     'inline_query_id' => $update->inline_query->id,
        //     'results' => json_encode([


        //         ])
        // ]);

    }
    elseif($update->message->text == '/contact')
    {
        $text = "کانال تلگرام مرتبط با این ربات : @TurkTV \n در صورتی که مشکل در کار با این ربات داشتید برای گزارش و ارسال پیام به برنامه نویس و تهیه کننده این ربات از طریق اکانت @alo_survivor در ارتباط باشید ";
        $response = simplemessage($update->message->chat->id,$text);
    }
    // elseif ($update->message->text == '/mp3') {
    //     $query = $fpdo->from('messages')->where('user_id',$update->message->from->id)->fetch();
    //     if ($query) {
    //         $values = array('user_id' => $update->message->from->id, 'chat_id' => $update->message->chat->id, 'message_id' => $update->message->message_id, 'daryaft' => 'mp3', 'ersal' => 'khali');
    //         $query = $fpdo->update('messages')->set($values)->where('id',$query[id])->execute();    

    //     }else{
    //         $values = array('user_id' => $update->message->from->id, 'chat_id' => $update->message->chat->id, 'message_id' => $update->message->message_id, 'daryaft' => 'mp3', 'ersal' => 'khali');       
    //         $query = $fpdo->insertInto('messages')->values($values)->execute();    
    //     }
    //     $response = $client->sendMessage([
    //         'chat_id' => $update->message->chat->id,
    //         'text' => "<b>adres ra vared konid:</b> ".json_encode($update->message),
    //         'parse_mode' => 'HTML',
    //         'reply_markup' => json_encode([
    //                 'resize_keyboard' => true,
    //                 'one_time_keyboard' => true,
    //                 'force_reply' => true
    //             ])
    //     ]);
    // }
    // elseif ($update->message->reply_to_message) {
    //     $response = $client->sendMessage([
    //         'chat_id' => $update->message->chat->id,
    //         'text' => "<b>adres ra vared konid:</b> ".json_encode($update->message),
    //         'parse_mode' => 'HTML',
    //         'reply_markup' => json_encode([
    //                 'resize_keyboard' => true,
    //                 'one_time_keyboard' => true,
    //                 'force_reply' => true
    //             ])
    //     ]);
    // }
    // elseif ($update->message->text == '/tv8') {
    //     $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    //     // $dom = new Dom;
    //     // $dom->load('http://www.tvyayinakisi.com/tv-8');
    //     // $html = $dom->outerHtml;
    //     // $roztime = $dom->find('span[class=date]')[0];
    //     // $btimes = $dom->find('div[class=two columns time]');
    //     // $progtitles = $dom->find('div[class=ten columns]');
    //     // $arri = '';
    //     // foreach ($btimes as $key => $btime) {
    //     //     $arri .= $btime->text.":".$progtitles[$key]->text."\n";
    //     // }
    //     $arri = listbarnameha();
    //     $response = $client->sendMessage([
    //         'chat_id' => $update->message->chat->id,
    //         'text' => "برنامه های کانال TV8 \n تاریخ امروز \n".$arri."آدرس کانال تلگرام » @TurkTv \n گزارش خطا: @alo_survivor"
    //     ]);
    // }
    // elseif ($update->message->text == '/vidiol') {
    //     $url = 'https://raw.githubusercontent.com/mohammad6006/geekswebbot/master/sample1mb.mp4';
    //         $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_video']);
    //         $response = $client->sendVideo([
    //             'chat_id'=> $update->message->chat->id,
    //             'video'=>fopen($url,'r'),
    //             'caption'=>'test'
    //             ]);
    // }
    // elseif ($update->message->text == '/grab') {
    //     $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_photo']);
    //     $media = Bolandish\Instagram::getMediaByHashtag("palang", 6);
    //     foreach($media as $value){
    //       if ($value->dimensions->width === $value->dimensions->height){
    //             $url = trim(strtok($value->display_src, '?')); 
    //             $response = $client->sendPhoto([
    //                 'chat_id'=> $update->message->chat->id,
    //                 'photo'=>fopen($url,'r'),
    //                 'caption'=>substr($value->caption, 0,190)
    //                 ]);
    //       }
    //     }
    // }
    // elseif (isset($update->callback_query)) {
    //     $response = $client->answerCallbackQuery([
    //         'callback_query_id' => $update->callback_query->id,
    //         'text' => 'call back query '
    //     ]);
    // }
    // elseif ($update->message->text == '/caltest') {
    //     $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    //     $response = $client->sendMessage([
    //         'chat_id' => $update->message->chat->id,
    //         'text' => 'contact us ',
    //         'reply_markup' => json_encode([
    //             'inline_keyboard' => [
    //                     [
    //                         ['text' => '+plus','callback_data'=>'1'],
    //                         ['text'=> '-min','callback_data'=>'2']
    //                     ],
    //                     [
    //                         ['text'=>'reset','callback_data'=>'0']
    //                     ]
    //                 ]
    //             ])

    //     ]);
    // }
    elseif (strpos(strtolower($update->message->text), '/mp3') === 0 ) {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $mpfile = explode(';', $update->message->text);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => $mpfile[1].$mpfile[2].$mpfile[3].$mpfile[4].$mpfile[5]
        ]);
        // /mp3;url.mp3;title;performer;caption;chatid
        // $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_audio']);
        // $response = $client->sendAudio([
        //     'chat_id' => $update->message->chat->id,
        //     'audio' => fopen($mpfile[1],'r'),
        //     'caption' => "Haydi Söyle \n Kalben \n @TurkTv",
        //     'performer' => '@TurkTv-Kalben',
        //     'title' => 'Haydi Söyle'
        //     ]);
    }
    elseif (strpos(strtolower($update->message->text), '/kanal') === 0 ) {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $kanalname = explode('-', $update->message->text);
        $logger->addInfo('kanal:'.json_encode($update->message));
        switch ($kanalname[1]) {
            case 'Tv8':
                $kanaln = 'tv-8';
                break;
            case 'StarTv':
                $kanaln = 'star-tv';
                break;
            case 'D':
                $kanaln = 'kanal-d-tv';
                break;
            case 'ShowTv':
                $kanaln = 'show-tv';
                break;
            case 'ATV':
                $kanaln = 'atv';
                break;
            case 'TRT1':
                $kanaln = 'trt-1';
                break;
            case '7':
                $kanaln = 'kanal-7';
                break;
            case 'Tv2':
                $kanaln = 'tv2';
                break;
            case 'FOX':
                $kanaln = 'fox';
                break;
            case 'CartoonNetwork':
                $kanaln = 'cartoon-network';
                break;
            default:
                $kanaln = "not found";
                break;
        }
        if ($kanaln == "not found") {
            $response = $client->sendMessage([
                'chat_id' => $update->message->chat->id,
                'text' => 'در حال حاضر فقط کانالهای پرطرفدار به لیست اضافه شده است. اگر کانال مورد علاقه شما در این لیست موجود نیست  از طریق اکانت @alo_survivor نام کانال را برای ما ارسال کنید تا به این لیست اضافه کنیم'
            ]);
        } else {
            $arri = listbarnameha($kanaln);
            $response = $client->sendMessage([
                'chat_id' => $update->message->chat->id,
                'text' => $arri
            ]);
        }
        
    }
    else if($update->message->text == '/start')
    {
        $logger->addInfo('start:'.$update->message->message_id.'-'.$update->message->date.'-'.$update->message->text.'-'.$update->message->from->id.'-'.$update->message->from->first_name.'-'.$update->message->from->username);
        $query = $fpdo->from('messages')->where('user_id',$update->message->from->id)->fetch();
        if ($query) {
           $logger->addInfo('karbar gablan sabt shode ast.');
           // $logger->addInfo(json_encode($query));
        }else{
            $values = array('user_id' => $update->message->from->id, 'chat_id' => $update->message->chat->id, 'message_id' => $update->message->message_id, 'daryaft' => 'start', 'ersal' => 'start');       
            $query = $fpdo->insertInto('messages')->values($values);    
            $insert = $query->execute();
        }
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => "کانال دلخواه خود را از لیست زیر انتخاب کنید \n در صورتی که کانال مورد نظر شما در لیست موجود نمیباشد گزینه دیگر کانالها را انتخاب کنید.",
            'reply_to_message_id' => $update->message->message_id,
            'reply_markup' => json_encode([
                    'keyboard'=> [
                        ['/kanal-Tv8','/kanal-StarTv','/kanal-D'],
                        ['/kanal-ShowTv','/kanal-ATV','/kanal-TRT1'],
                        ['/kanal-7','/kanal-Tv2','/kanal-FOX'],
                        ['/kanal-دیگر کانالها','/kanal-CartoonNetwork'],
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ]),
            ]);

    }
    elseif ($update->message->audio && ($update->message->chat->username == 'Mohammad6006')) {
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => 'fileid:'.$update->message->audio->file_id.' duration: '.$update->message->audio->duration.' performer: '.$update->message->audio->performer.' title: '.$update->message->audio->title.' mime_type: '.$update->message->audio->mime_type.' file_size: '.$update->message->audio->file_size
            ]);
        $response = $client->sendAudio([
            'chat_id' => $update->message->chat->id,
            'audio' => $update->message->audio->file_id,
            'performer' => "@TurkTv",
            'title' => $update->message->audio->title,
            'caption' => $update->message->audio->title."\n @TurkTv",
            'duration' => $update->message->audio->duration
            ]);

// $values = array('user_id' => $update->message->chat->id, 'chat_id' => $update->message->chat->id, 'message_id' => '456', 'daryaft' => 'abc', 'ersal' => 'def');       
//     $query = $fpdo->insertInto('messages')->values($values);    
//     $insert = $query->execute();
    }
    elseif ($update->message->video && ($update->message->chat->username == 'Mohammad6006')) {
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => 'fileid:'.$update->message->video->file_id.' width: '.$update->message->video->width.' height: '.$update->message->video->height.' duration: '.$update->message->video->duration.' mime_type: '.$update->message->video->mime_type.' file_size: '.$update->message->video->file_size
            ]);
        $response = $client->sendVideo([
            'chat_id' => '@turktv',
            'video' => $update->message->video->file_id,
            'caption' => " \n @TurkTv",
            'duration' => $update->message->video->duration,
            'width' => $update->message->video->width,
            'height' => $update->message->video->height
            ]);
    }
    // elseif (strpos(strtolower($update->message->text),'/instagram') == 0) {
        
    //     // $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_photo']);
    //     $insa = explode(' ', $update->message->text);
    //     $response = $client->sendMessage([
    //         'chat_id' => $update->message->chat->id,
    //         'text' => $insa[0].'-'.$insa[1].'-'.$insa[2]
    //     ]);
    //     // $media = Bolandish\Instagram::getMediaByHashtag($insa[1], intval($insa[2]));
    //     // foreach($media as $value){
    //     //   if ($value->dimensions->width === $value->dimensions->height){
    //     //         $url = trim(strtok($value->display_src, '?')); 
    //     //         $response = $client->sendPhoto([
    //     //             'chat_id'=> $update->message->chat->id,
    //     //             'photo'=>fopen($url,'r'),
    //     //             'caption'=>substr($value->caption, 0,190)
    //     //             ]);
    //     //   }
    //     // }
    // }
    // elseif($update->message->text == '/help')
    // {
    //     $tried = $update->callback_query->data+1;
    //     $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    //     $response = $client->sendMessage([
    //         'chat_id' => $update->message->chat->id,
    //         'text'=>"اولین تلاش \n زمان :\n ".date('d M y -  h:i:s'),
    // 'reply_markup'=>json_encode([
    //     'inline_keyboard'=>[
    //         [
    //             ['text'=>'yahoo','callback_data'=>"$tried"],
    //             ['text'=>'msn','url'=>'http://msn.com']
    //         ],
    //         [
    //             ['text'=>'google','url'=>'http://google.com']
    //         ]
    //     ]
    // ])
    //         ]);

    // }
    // else if($update->message->text == '/latest')
    // {
    //         Feed::$cacheDir     = __DIR__ . '/cache';
    //         Feed::$cacheExpire  = '5 hours';
    //         $rss        = Feed::loadRss($url);
    //         $items      = $rss->item;
    //         $lastitem   = $items[0];
    //         $lastlink   = $lastitem->link;
    //         $lasttitle  = $lastitem->title;
    //         $message = $lasttitle . " \n ". $lastlink;
    //         $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    //         $response = $client->sendMessage([
    //                 'chat_id' => $update->message->chat->id,
    //                 'text' => $message
    //             ]);

    // }
    // elseif ($update->message->reply_to_message) {
    //     $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    //     $response = $client->sendMessage([
    //         'chat_id' => $update->message->chat->id,
    //         'text' => "پیامهای خود در رابطه با کانال و برنامه نظرسنجی به این اکانت بفرستید: @alo_survivor"
    //     ]);
    // }
    // elseif ($update->message->document) {
    //     $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    //     $response = $client->getFile([
    //             'file_id'=> $update->message->document->file_id
    //         ]);
    //     $response = $client->sendDocument([
    //         'chat_id'=> $update->message->chat->id,
    //         'document'=>$update->message->document->file_id,
    //         'caption'=>'@TurkTv'
    //         ]);
    // }
    // elseif ($update->message->photo) {
    //     $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_photo']);
    //     $response = $client->getFile([
    //             'file_id'=> $update->message->photo[0]->file_id
    //         ]);
    //     $response = $client->sendPhoto([
    //         'chat_id'=> $update->message->chat->id,
    //         'photo'=>$update->message->photo[0]->file_id,
    //         'caption'=>'@TurkTv'
    //         ]);
    // }
    // elseif ($update->message->entities[0]->type == 'url') {
    //     $url = $update->message->text;
    //     $clasih = new InstagramDownload($url);
    //     $url = $clasih->downloadUrl();
    //     $type1 = $clasih->type();
    //     if ($type1 == 'image') {
    //         $url = trim(strtok($url, '?'));
    //         if ($url != '') {
    //             $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_photo']);
    //             $response = $client->sendPhoto([
    //                 'chat_id'=> $update->message->chat->id,
    //                 'photo'=>fopen($url,'r'),
    //                 'caption'=>'test'
    //                 ]);
    //         }else{
    //             $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    //             $response = $client->sendMessage([
    //                 'chat_id' => $update->message->chat->id,
    //                 'text' => 'olmadi - not found'
    //             ]);
    //         }
    //     }elseif ($type1 == 'video') {
    //         $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_video']);
    //         $response = $client->sendVideo([
    //             'chat_id'=> $update->message->chat->id,
    //             'video'=>fopen($url,'r'),
    //             'caption'=>'test'
    //             ]);
    //     }else{
    //         $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    //         $response = $client->sendMessage([
    //             'chat_id' => $update->message->chat->id,
    //             'text' => 'olmadi'
    //         ]);
    //     }
        // print_r($response);
        // $ch = curl_init('https://d3k90kvix375hb.cloudfront.net/assets/home/hero/startup-10d700b2164d8d9ceb3934c15f01277c7a4bb2ce9d9c1d14d0bd00d680debafc.png');
        // $fp = fopen('flower.png', 'wb');
        // curl_setopt($ch, CURLOPT_FILE, $fp);
        // curl_setopt($ch, CURLOPT_HEADER, 0);
        // curl_exec($ch);
        // curl_close($ch);
        // fclose($fp);
        // $response = $client->getFile([
        //         'file_id'=> file_get_contents($url)
        //     ]);
        // return $response;
        // $img = curl_file_create('startup-10d700b2164d8d9ceb3934c15f01277c7a4bb2ce9d9c1d14d0bd00d680debafc.png','image/png');
        // $response = $client->sendPhoto([
        //     'chat_id'=> $update->message->chat->id,
        //     'photo'=>$img,
        //     'caption'=>'@TurkTv'
        //     ]);
        // $response = $client->sendPhoto([
        //     'chat_id'=> $update->message->chat->id,
        //     'photo'=>file_get_contents('https://d3k90kvix375hb.cloudfront.net/assets/home/hero/startup-10d700b2164d8d9ceb3934c15f01277c7a4bb2ce9d9c1d14d0bd00d680debafc.png'),
        //     'caption'=>'@TurkTv111'
        //     ]);
    // }
    else
    {
        $logger->addInfo('namoshakhas:'.$update->message->text.'-'.$update->message->chat->id.'-'.$update->message->chat->first_name.'-'.$update->message->chat->username);
        $daryafti = 'Not detect';
        $daryafti = $update->message->text;
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => 'دستور ارسالی شما در این ربات تعریف نشده است برای ارسال گزارش مشکل خود به اکانت @alo_survivor پیام خود را ارسال کنید'
            ]);
    }

} catch (Zelenin\Telegram\Bot\Exception\NotOkException $e) {
        $logger->addInfo('catch error:'.$e->getMessage().' - chatid:'.$update->message->chat->id.'-'.$update->message->chat->first_name.'-'.$update->message->chat->username);

    //echo error message ot log it
    //echo $e->getMessage();

}
