        // $logger->addInfo('inline_query - chatid:'.$update->inline_query->id.'-'.$update->inline_query->query);
        // $response = $client->answerInlineQuery([
        //     'inline_query_id' => $update->inline_query->id,
        //     'results' => json_encode([
        //         ])
        // ]);

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
// $values = array('user_id' => $update->message->chat->id, 'chat_id' => $update->message->chat->id, 'message_id' => '456', 'daryaft' => 'abc', 'ersal' => 'def');       
//     $query = $fpdo->insertInto('messages')->values($values);    
//     $insert = $query->execute();
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
            // /mp3;url.mp3;title;performer;caption;chatid
        // $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_audio']);
        // $response = $client->sendAudio([
        //     'chat_id' => $update->message->chat->id,
        //     'audio' => fopen($mpfile[1],'r'),
        //     'caption' => "Haydi Söyle \n Kalben \n @TurkTv",
        //     'performer' => '@TurkTv-Kalben',
        //     'title' => 'Haydi Söyle'
        //     ]);