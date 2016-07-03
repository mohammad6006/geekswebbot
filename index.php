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
require 'vendor/autoload.php';

$client = new Zelenin\Telegram\Bot\Api('235690241:AAFNMapgWZpOQIswS51FdqY0tjiXOdzNJus'); // Set your access token
$url = 'http://feeds.feedburner.com/eu/NlGz'; // URL RSS feed
$update = json_decode(file_get_contents('php://input'));

//your app
try {

    if($update->message->text == '/contact')
    {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => "پیامهای خود در رابطه با کانال و برنامه نظرسنجی به این اکانت بفرستید: @alo_survivor"
        ]);
    }
    else if($update->message->text == '/help')
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
            'text' => "به نظرسنجی برنامه سوروایور خوش آمدید :\n این نظرسنجی به منظور استفاده در کانال @TurkTv اکاربرد دارد
            و نتیجه این نظرسنجی از طریق کانال اطلاع رسانی میشود:\n
            برای ارتباط با ادمین کانال و برنامه نویس : @alo_survivor"
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
    else
    {
        $update->message->text = json_encode($mimik);
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => json_encode($update->message->text)
            ]);
    }

} catch (\Zelenin\Telegram\Bot\NotOkException $e) {

    //echo error message ot log it
    //echo $e->getMessage();

}
