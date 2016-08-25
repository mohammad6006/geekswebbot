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

$token = getenv('acstok');
$client = Zelenin\Telegram\Bot\ApiFactory::create($token);

// $url = 'http://feeds.feedburner.com/eu/NlGz'; // URL RSS feed
$update = json_decode(file_get_contents('php://input'));
//your app
try {

    if($update->message->text == '/contact')
    {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => 'contact us '
        ]);
    }
    elseif ($update->message->text == '/grab') {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_photo']);
        $media = Bolandish\Instagram::getMediaByHashtag("palang", 6);
        foreach($media as $value){
          if ($value->dimensions->width === $value->dimensions->height){
                $url = trim(strtok($value->display_src, '?')); 
                $response = $client->sendPhoto([
                    'chat_id'=> $update->message->chat->id,
                    'photo'=>fopen($url,'r'),
                    'caption'=>substr($value->caption, 0,190)
                    ]);
          }
        }
    }
    elseif (isset($update->callback_query)) {
        $response = $client->answerCallbackQuery([
            'callback_query_id' => $update->callback_query->id,
            'text' => 'call back query '
        ]);
    }
    elseif ($update->message->text == '/caltest') {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => 'contact us ',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                        [
                            ['text' => '+plus','callback_data'=>'1'],
                            ['text'=> '-min','callback_data'=>'2']
                        ],
                        [
                            ['text'=>'reset','callback_data'=>'0']
                        ]
                    ]
                ])

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
    elseif($update->message->text == '/help')
    {
        $tried = $update->callback_query->data+1;
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text'=>"اولین تلاش \n زمان :\n ".date('d M y -  h:i:s'),
    'reply_markup'=>json_encode([
        'inline_keyboard'=>[
            [
                ['text'=>'yahoo','callback_data'=>"$tried"],
                ['text'=>'msn','url'=>'http://msn.com']
            ],
            [
                ['text'=>'google','url'=>'http://google.com']
            ]
        ]
    ])
            ]);

    }
    else if($update->message->text == '/start')
    {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => "test\n test"
            ]);
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => "ماه تولد خودتونو انتخاب کنید:",
            'reply_markup' => json_encode([
                    'keyboard'=> [
                        ['فروردین','اردیهشت','خرداد'],
                        ['تیر','مرداد','شهریور'],
                        ['مهر','آبان','آذر'],
                        ['دی','بهمن','اسفند'],
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ]),
            ]);

    }
    else if($update->message->text == '/latest')
    {
            Feed::$cacheDir     = __DIR__ . '/cache';
            Feed::$cacheExpire  = '5 hours';
            $rss        = Feed::loadRss($url);
            $items      = $rss->item;
            $lastitem   = $items[0];
            $lastlink   = $lastitem->link;
            $lasttitle  = $lastitem->title;
            $message = $lasttitle . " \n ". $lastlink;
            $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
            $response = $client->sendMessage([
                    'chat_id' => $update->message->chat->id,
                    'text' => $message
                ]);

    }
    elseif ($update->message->reply_to_message) {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => "پیامهای خود در رابطه با کانال و برنامه نظرسنجی به این اکانت بفرستید: @alo_survivor"
        ]);
    }
    elseif ($update->message->document) {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->getFile([
                'file_id'=> $update->message->document->file_id
            ]);
        $response = $client->sendDocument([
            'chat_id'=> $update->message->chat->id,
            'document'=>$update->message->document->file_id,
            'caption'=>'@TurkTv'
            ]);
    }
    elseif ($update->message->photo) {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_photo']);
        $response = $client->getFile([
                'file_id'=> $update->message->photo[0]->file_id
            ]);
        $response = $client->sendPhoto([
            'chat_id'=> $update->message->chat->id,
            'photo'=>$update->message->photo[0]->file_id,
            'caption'=>'@TurkTv'
            ]);
    }
    elseif ($update->message->entities[0]->type == 'url') {
        $url = $update->message->text;
        $clasih = new InstagramDownload($url);
        $url = $clasih->downloadUrl();
        $type1 = $clasih->type();
        if ($type1 == 'image') {
            $url = trim(strtok($url, '?'));
            if ($url != '') {
                $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_photo']);
                $response = $client->sendPhoto([
                    'chat_id'=> $update->message->chat->id,
                    'photo'=>fopen($url,'r'),
                    'caption'=>'test'
                    ]);
            }else{
                $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
                $response = $client->sendMessage([
                    'chat_id' => $update->message->chat->id,
                    'text' => 'olmadi - not found'
                ]);
            }
        }elseif ($type1 == 'video') {
            $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_video']);
            $response = $client->sendVideo([
                'chat_id'=> $update->message->chat->id,
                'video'=>fopen($url,'r'),
                'caption'=>'test'
                ]);
        }else{
            $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
            $response = $client->sendMessage([
                'chat_id' => $update->message->chat->id,
                'text' => 'olmadi'
            ]);
        }
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
    }
    else
    {
        $daryafti = 'Not detect';
        $daryafti = $update->message->text;
        switch ($daryafti) {
            case 'فروردین':
                $daryafti = 'koc';
                break;
            case 'اردیهشت':
                $daryafti = 'terazi';
                break;
            default:
                $daryafti = 'yaft nashod';
                break;
        }
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => $daryafti
            ]);
    }

} catch (\Zelenin\Telegram\Bot\NotOkException $e) {

    //echo error message ot log it
    //echo $e->getMessage();

}
