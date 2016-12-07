<?php
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
\Cloudinary::config(array( 
  "cloud_name" => "drnd9jbicz", 
  "api_key" => "621626275129456", 
  "api_secret" => "cPm98hx-4z3V8CSB7vcVOslB1zM" 
));



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
function simpleTextSend($chatid,$text)
{
    global $client;
    try {
        $response = $client->sendChatAction(['chat_id' => $chatid, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $chatid,
            'text' => $text,
            'parse_mode' => 'HTML'
        ]); 
    return $response;       
    } catch (Exception $e) {
    }
}

try {
    if(isset($update->inline_query))
    {

    }
    elseif ($update->message->entities[0]->type == 'url') {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $query = $fpdo->from('messages')->where('user_id',$update->message->from->id)->fetch();
        if ($query) {
            $values = array('user_id' => $update->message->from->id, 'chat_id' => $update->message->chat->id, 'message_id' => $update->message->message_id, 'daryaft' => $update->message->text, 'ersal' => 'khali');
            $query = $fpdo->update('messages')->set($values)->where('id',$query[id])->execute();    

        }else{
            $values = array('user_id' => $update->message->from->id, 'chat_id' => $update->message->chat->id, 'message_id' => $update->message->message_id, 'daryaft' => $update->message->text, 'ersal' => 'khali');       
            $query = $fpdo->insertInto('messages')->values($values)->execute();    
        }
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => 'از این آدرس چه میخواهید؟',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                        [
                            ['text' => 'دانلود این تصویر از اینستاگرام','callback_data'=>'urltoinstapic']
                        ],
                        [
                            ['text' => 'دانلود این ویدئو از اینستاگرام','callback_data'=>'urltoinstavideo']
                        ],
                        [
                            ['text'=>'تبدیل این ادرس به موزیک','callback_data'=>'urltoaudio']
                        ],
                        [
                            ['text' => 'تبدیل این آدرس به ویدئو','callback_data'=>'urltovideo']
                        ]
                    ]
                ])
            ]);
    }
    elseif($update->message->text == '/contact')
    {
        $text = "کانال تلگرام مرتبط با این ربات : @TurkTV \n در صورتی که مشکل در کار با این ربات داشتید برای گزارش و ارسال پیام به برنامه نویس و تهیه کننده این ربات از طریق اکانت @alo_survivor در ارتباط باشید ";
        $response = simpleTextSend($update->message->chat->id,$text);
    }
    elseif (strpos(strtolower($update->message->text), '/dizi') === 0 ) {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => 'نام سریال مورد نظر خود را انتخاب کنید:',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                        [
                            ['text' => 'Anne','callback_data'=>'dizi_anne']
                        ],
                        [
                            ['text' => 'Kiralık Aşk','callback_data'=>'dizi_kiralikask']
                        ],
                        [
                            ['text'=>'Aşk Laftan Anlamaz','callback_data'=>'dizi_AskLaftan']
                        ],
                        [
                            ['text' => 'İçerde','callback_data'=>'dizi_icerde']
                        ],
                        [
                            ['text'=>'Kara Sevda','callback_data'=>'dizi_KaraSevda']
                        ]
                    ]
                ])
            ]);
    }
    elseif (isset($update->callback_query)) {
        $dastor = $update->callback_query->data;
        if ($dastor == 'urltoinstapic' || $dastor == 'urltoinstavideo') {
            $query = $fpdo->from('messages')->where('user_id',$update->callback_query->from->id)->fetch();
            $url = $query[daryaft];

            $clasih = new InstagramDownload($url);
            $url = $clasih->downloadUrl();
            $type1 = $clasih->type();
            if ($type1 == 'image') {
                $url = trim(strtok($url, '?'));
                if ($url != '') {
                    $response = $client->sendChatAction(['chat_id' => $update->callback_query->message->chat->id, 'action' => 'upload_photo']);
                    $response = $client->sendPhoto([
                        'chat_id'=> $update->callback_query->message->chat->id,
                        'photo'=>fopen($url,'r'),
                        'caption'=>'test'
                        ]);
                }else{
                    $text = "در حال حاضر قابلیت دانلود برای پروفایل های عمومی امکانپذیر می باشد \n @TurkTv \n @TurkTvBot";
                    simpleTextSend($update->callback_query->message->chat->id,$text);
                }
            }elseif ($type1 == 'video') {
                $url = trim(strtok($url, '?'));
                if ($url != '') {
                    $response = $client->sendChatAction(['chat_id' => $update->callback_query->message->chat->id, 'action' => 'upload_video']);
                    $response = $client->sendVideo([
                        'chat_id'=> $update->callback_query->message->chat->id,
                        'video'=>fopen($url,'r'),
                        'caption'=>'test'
                        ]);
                }else{
                    $text = "در حال حاضر قابلیت دانلود برای پروفایل های عمومی امکانپذیر می باشد \n @TurkTv \n @TurkTvBot";
                    simpleTextSend($update->callback_query->message->chat->id,$text);
                }
            }else{
                $text = "در حال حاضر قابلیت دانلود برای پروفایل های عمومی امکانپذیر می باشد \n @TurkTv \n @TurkTvBot";
                simpleTextSend($update->callback_query->message->chat->id,$text);
            }


        }elseif ($dastor == 'urltoaudio') {
            $query = $fpdo->from('messages')->where('user_id',$update->callback_query->from->id)->fetch();
            $tem = $query[daryaft];
        $ssii = \Cloudinary\Uploader::upload($tem, array("resource_type" => "auto","timeout" => 60,"audio_codec" => "mp3"));

            simpleTextSend($update->callback_query->message->chat->id,$ssii[secure_url]);
            $response = $client->sendChatAction(['chat_id' => $update->callback_query->message->chat->id, 'action' => 'upload_audio']);
            $response = $client->sendAudio([
                'chat_id' => $update->callback_query->message->chat->id,
                'audio' => $ssii[secure_url],
                'title' => '@TurkTv-test',
                'caption' => 'dasdasd',
                'performer' => 'dasdasdas'
                ]);
                simpleTextSend($update->callback_query->message->chat->id,json_encode($response));  
        }
        // $diziinsta = Bolandish\Instagram::getMediaByHashtag("karasevda", 2);
        // Bolandish\Instagram::getMediaAfterByUserID(460563723, 1060728019300790746, 10);

    }
    elseif (strpos(strtolower($update->message->text), '/mp3') === 0 ) {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => 'نام سریال مورد نظر خود را انتخاب کنید:',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                        [
                            ['text' => 'Kiralık Aşk','callback_data'=>'dizi_kiralikask']
                        ],
                        [
                            ['text'=>'Aşk Laftan Anlamaz','callback_data'=>'dizi_AskLaftan']
                        ],
                        [
                            ['text' => 'İçerde','callback_data'=>'dizi_icerde']
                        ],
                        [
                            ['text'=>'Kara Sevda','callback_data'=>'dizi_KaraSevda']
                        ]
                    ]
                ])
            ]);
            // 'text' => "/dizi_kiralikask : <b>Kiralık_Aşk</b> \n /dizi_AskLaftan :<b>Aşk Laftan Anlamaz</b> \n /dizi_icerde : <b>İçerde</b> \n /dizi_KaraSevda :<b>Kara Sevda</b>",
            // 'parse_mode' => 'HTML'
        // $mpfile = explode(';', $update->message->text);
        // $response = $client->sendMessage([
        //     'chat_id' => $update->message->chat->id,
        //     'text' => $mpfile[1].$mpfile[2].$mpfile[3].$mpfile[4].$mpfile[5]
        // ]);

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
            'caption' => "dasdasfdsf @TurkTv",
            'performer' => "@TurkTv",
            'title' => 'aasdasd'
            ]);

    }
    elseif ($update->message->video && ($update->message->chat->username == 'Mohammad6006')) {
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => 'fileid:'.$update->message->video->file_id.' width: '.$update->message->video->width.' height: '.$update->message->video->height.' duration: '.$update->message->video->duration.' mime_type: '.$update->message->video->mime_type.' file_size: '.$update->message->video->file_size
            ]);
        $response = $client->sendVideo([
            'chat_id' => '@turktv',
            'video' => $update->message->video->file_id,
            'caption' => $update->message->caption,
            'duration' => $update->message->video->duration,
            'width' => $update->message->video->width,
            'height' => $update->message->video->height
            ]);
    }

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
