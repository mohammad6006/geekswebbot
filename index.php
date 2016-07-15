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

class InstagramDownload {
  public $input_url;
  public $id;
  
  public $type = 'image';
  public $download_url;
  public $meta_values = array();
  
  public  $error_code = 0;
  
  const INSTAGRAM_DOMAIN = 'instagram.com';
  
  
  function __construct($url = '') {
    if (!empty($url)) {
      $this->setUrl($url);
    }
  }
  
  public function setUrl($url) {
    $id = $this->validateUrl($url);
    if ($id && !is_numeric($id)) {
      $this->id = $id; 
      $this->input_url = $url;
    }
    else {
      $this->id = FALSE;
    }
  }
  
  public function type() {
    return $this->type;
  }
  
  public function downloadUrl($force_dl = TRUE) {
    if ($this->getError()) {
      return FALSE;
    }
    $status = $this->process($this->input_url);
    if ($status) {
      if ($force_dl) {
        return $this->download_url . '?dl=1';
      }
      return $this->download_url;
    }
    return FALSE;
  }

  protected function process(){
    $this->fetch($this->input_url);
    if (!is_array($this->meta_values)) {
      $this->meta_values = array();
      return FALSE;
    }
    if (!empty($this->meta_values['og:video'])) {
      $this->type = 'video';
      $this->download_url = $this->meta_values['og:video'];
    }
    elseif (!empty($this->meta_values['og:image'])) {
      $this->type = 'image';
      $this->download_url = $this->meta_values['og:image'];
    }
    else {
      return FALSE;
    }
    return $this->download_url;
  }

  public function validateUrl($url = NULL) {
    if (is_null($url) && isset($this->input_url)) {
      $url = $this->input_url;
    }
    $url = parse_url($url);
    if (empty($url['host'])) {
      $this->error_code = -1;
      return FALSE;
    }
    
    $url['host'] = strtolower($url['host']);
    
    if ($url['host'] != self::INSTAGRAM_DOMAIN && $url['host'] != 'www.' . self::INSTAGRAM_DOMAIN) {

      $this->error_code = -2;
      return FALSE;
    }
    if (empty($url['path'])) {
      $this->error_code = -3;
      return FALSE;
    }
    $args = explode('/', $url['path']);
    if (!empty($args[1]) && $args[1] == 'p' && isset($args[2], $args[2][4]) && !isset($args[2][255])) {
      $this->error_code = 0;
      return $args[2];
    }
    $this->error_code = -4;
    return FALSE;
  }

  protected function fetch($URI) {
    $curl = curl_init($URI);

    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 15);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
      curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    }
    

    $response = curl_exec($curl);

    curl_close($curl);

    if(!empty($response)) {
      return $this->_parse($response);
    }
    else {
      return false;
    }
  }

  protected function _parse($HTML) {
    $rawTags = array();

    preg_match_all("|<meta[^>]+=\"([^\"]*)\"[^>]" . "+content=\"([^\"]*)\"[^>]+>|i", $HTML, $rawTags, PREG_PATTERN_ORDER);

    if(!empty($rawTags)) {
      $multiValueTags = array_unique(array_diff_assoc($rawTags[1], array_unique($rawTags[1])));

      for($i=0; $i < sizeof($rawTags[1]); $i++) {
        $hasMultiValues = false;
        $tag = $rawTags[1][$i];

        foreach($multiValueTags as $mTag) {
          if($tag == $mTag)
            $hasMultiValues = true;
        }

        if($hasMultiValues) {
          $this->meta_values[$tag][] = $rawTags[2][$i];
        }
        else {
          $this->meta_values[$tag] = $rawTags[2][$i];
        }
      }
    }

    if (empty($this->meta_values)) { return false; }

    return $this->meta_values;
  }

  public function getError() {
    if ($this->error_code !== TRUE && $this->error_code !== 0) {
      return self::error($this->error_code);
    }
    return NULL;
  }

  static function error($id) {
    $errors = array(
      -1 => 'Invalid URL',
      -2 => 'Entered URL is not an ' . self::INSTAGRAM_DOMAIN . ' URL.',
      -3 => 'No image or video found in this URL',
      -4 => 'No image or video found in this URL',
    );

    if (isset($errors[$id])) {
      return $errors[$id];
    }
    return 'Unknown error';
  }
}

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
        // $response = $client->sendMessage([
        //     'chat_id' => $update->message->chat->id,
        //     'text' => $update->message->chat->id.'-'.$update->message->document->file_id
        // ]);

    }
    elseif ($update->message->photo) {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_photo']);
        $response = $client->getFile([
                'file_id'=> $update->message->photo[0]->file_id
            ]);
        // $response = $client->sendMessage([
        //     'chat_id' => $update->message->chat->id,
        //     'text' => $update->message->chat->id.'-'.$update->message->photo[0]->file_id
        // ]);
        $response = $client->sendPhoto([
            'chat_id'=> $update->message->chat->id,
            'photo'=>$update->message->photo[0]->file_id,
            'caption'=>'@TurkTv'
            ]);
    }
    elseif ($update->message->entities[0]->type == 'url') {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_photo']);
        // $response = $client->getFile([
        //         'file_id'=> $update->message->photo[0]->file_id
        //     ]);
        $url = $update->message->text;
        $clasih = new InstagramDownload($url);
        $url = $clasih->downloadUrl();
        $instaImg2 = $client->downloadUrl(TRUE);
        $error1 = $client->getError();
        $type1 = $client->type();
        // $response = $client->sendPhoto([
        //     'chat_id'=> $update->message->chat->id,
        //     'photo'=>fopen($instaImg,'r'),
        //     'caption'=>'@TurkTv'
        //     ]);
        if (isset($url)) {
            $response = $client->sendMessage([
                'chat_id' => $update->message->chat->id,
                'text' => $url
            ]);
        }else{
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
