<?php
require 'InstagramDownload.class.php';
require 'vendor/autoload.php';
use PHPHtmlParser\Dom;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Abraham\TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', getenv('CONSUMER_KEY'));
define('CONSUMER_SECRET', getenv('CONSUMER_SECRET'));
define('ACCESS_TOKEN', getenv('ACCESS_TOKEN'));
define('ACCESS_TOKEN_SECRET', getenv('ACCESS_TOKEN_SECRET'));

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
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);

\Cloudinary::config(array( 
  "cloud_name" => "drnd9jbicz", 
  "api_key" => "621626275129456", 
  "api_secret" => "cPm98hx-4z3V8CSB7vcVOslB1zM" 
));
$fekeransfile = file_get_contents("./ferekans.json");
$json_o=json_decode($fekeransfile);
$survfile = file_get_contents("./survivor2018.json");
$json_surv=json_decode($survfile);


function zamanmahali($zaman)
{
    $zaman1 = strtotime($zaman) + strtotime('00:30');
    return date('H:i',$zaman1);
}
function digarlistkanal($kanal)
{
    $dom = new Dom;
    $dom->load('https://www.tv360.com.tr/BroadStream-Index');
    $html = $dom->outerHtml;
    $day_of_week = date('N', strtotime('Today'));
    $ptime = $dom->find('div[id=tab-'.$day_of_week.'] div[class=saat] span');
    $pname = $dom->find('div[id=tab-'.$day_of_week.'] div[class=programadi] span');
    $arri = "لیست برنامه های امروز {$kanal} \n زمان برنامه ها به وقت ایران میباشد \n";
    foreach ($ptime as $key => $prog) {
        $arri .= zamanmahali($prog->text).":".$pname[$key]->text."\n";
    }
    return $arri .= "\n آدرس کانال : @TurkTv \n کانال ویدئویی: @canli \n ربات راهنما : @TurkTvBot";
}
function listbarnameha($kanal)
{
    $dom = new Dom;
    $dom->load('http://www.tvyayinakisi.com/'.$kanal);
    $html = $dom->outerHtml;
    $bnames = $dom->find('div[class=active] ul li');
    // $roztime = $dom->find('span[class=date]')[0];
    // $btimes = $dom->find('div[class=two columns time]');
    // $progtitles = $dom->find('div[class=ten columns]');
    $arri = "لیست برنامه های امروز {$kanal} \n زمان برنامه ها به وقت ایران میباشد \n";
    foreach ($bnames as $key => $value) {
        $arri .= zamanmahali($value->{'data-start'}).":".$value->find('p[class="name"]')->text."\n";
    }
    return $arri .= "\n آدرس کانال : @TurkTv \n کانال ویدئویی: @canli \n ربات راهنما : @TurkTvBot";
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
        return 'asd';
    }
    elseif ($update->message->reply_to_message->audio) {
        if ($update->message->text == '@turktv' && $update->message->chat->username == 'Mohammad6006') {
            $response = $client->sendAudio([
                'chat_id' => '@turktv',
                'audio' => $update->message->reply_to_message->audio->file_id,
                'caption' => $update->message->reply_to_message->caption
                ]);
        }else{
            // simpleTextSend($update->message->chat->id,json_encode($update->message));
            $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'upload_audio']);
            $response = $client->sendAudio([
                'chat_id' => $update->message->chat->id,
                'audio' => $update->message->reply_to_message->audio->file_id,
                'caption' => $update->message->text
                ]);            
        }

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
        // $quu = array(
        //   "q" => "#karasevda",
        //   "result_type"=> "recent",
        //   "count"=>4
        // );
        // $results = $connection->get('search/tweets', $quu);
        // foreach ($results->statuses as $twit) {
        //     $response = simpleTextSend($update->message->chat->id,$twit->text);
        // }
 
        $text = "کانال تلگرام مرتبط با این ربات : @TurkTV \n در صورتی که مشکل در کار با این ربات داشتید برای گزارش و ارسال پیام به برنامه نویس و تهیه کننده این ربات از طریق اکانت @alo_survivor در ارتباط باشید ";
        $response = simpleTextSend($update->message->chat->id,$text);
    }
    elseif($update->message->text == '/tv360')
    {
        $arri = digarlistkanal('360tv');
        $response = simpleTextSend($update->message->chat->id,$arri);
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

            // simpleTextSend($update->callback_query->message->chat->id,json_encode($ssii));
            $response = $client->sendChatAction(['chat_id' => $update->callback_query->message->chat->id, 'action' => 'upload_audio']);
            $response = $client->sendAudio([
                'chat_id' => $update->callback_query->message->chat->id,
                'audio' => $ssii[secure_url]
                ]);
                // simpleTextSend($update->callback_query->message->chat->id,json_encode($response));  
        }elseif ($dastor == 'survivor') {
            $response = $client->sendMessage([
                'chat_id' => $update->callback_query->message->chat->id,
                'text' => 'گروه مورد نظر خود را انتخاب کنید:',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                            [
                                ['text' => 'Gönüllüler','callback_data'=>'surv;gonulluler']
                            ],
                            [
                                ['text' => 'AllStar','callback_data'=>'surv;unluler']
                            ]
                        ]
                    ])
                ]);
        }elseif(strpos(strtolower($dastor), 'ferekans;') === 0){
            $kanaln = explode(';', $dastor);
            $ferekanstv = 'فرکانس شبکه '.$json_o->{$kanaln[1]}->normal->name."در مسیر ترکست 42درجه شرقی \n رسیور عادی \n Frekans: ".$json_o->{$kanaln[1]}->normal->ferekans."\n Polarizasyon: ".$json_o->{$kanaln[1]}->normal->polariz."\n SR: ".$json_o->{$kanaln[1]}->normal->SR."\n FEC: ".$json_o->{$kanaln[1]}->normal->FEC."\n TIP: ".$json_o->{$kanaln[1]}->normal->TIP."\n ______ \n رسیورهای اچ دی \n ".$json_o->{$kanaln[1]}->hd->name." \n Frekans: ".$json_o->{$kanaln[1]}->hd->ferekans."\n Polarizasyon: ".$json_o->{$kanaln[1]}->hd->polariz."\n SR: ".$json_o->{$kanaln[1]}->hd->SR."\n FEC: ".$json_o->{$kanaln[1]}->hd->FEC."\n TIP: ".$json_o->{$kanaln[1]}->hd->TIP."\n ______ \n فرکانس بین الملل \n".$json_o->{$kanaln[1]}->int->name." \n Frekans: ".$json_o->{$kanaln[1]}->int->ferekans."\n Polarizasyon: ".$json_o->{$kanaln[1]}->int->polariz."\n SR: ".$json_o->{$kanaln[1]}->int->SR."\n FEC: ".$json_o->{$kanaln[1]}->int->FEC."\n TIP: ".$json_o->{$kanaln[1]}->int->TIP."\n آدرس کانال : @TurkTv \n کانال ویدئو : @Canli \n ربات کمکی : @TurkTvBot \n اگر اشکالی در این ربات مشاهده کردید یا درخواستی داشتید به این اکانت اطلاع بدید : @alo_survivor";
            simpleTextSend($update->callback_query->message->chat->id,$ferekanstv);
            $response = $client->answerCallbackQuery([
                'callback_query_id' => $update->callback_query->id,
                'text' => 'فرکانس با موفقیت ارسال شد '
            ]);
        }elseif (strpos(strtolower($dastor), 'surv;') === 0) {
            $dizin = explode(';', $dastor);
            $btns = [];
            if ($dizin[1]=='unluler') {
                $pre = 'unl;';
                $imgroup = 'http://im.haberturk.com/2018/02/02/ver1517587895/1821354_620x410.jpg';
            }else{
                $pre = 'gon;';
                $imgroup = 'http://vayguzzum.com/images/2018_02/2018_02_07/acun-ilicali-survivor-2018-all-star-ve-gonulluler-takimlarinin-kadrolarini-acikladi--0702181200_m2.jpg';
            }
            foreach ($json_surv->{$dizin[1]} as $value) {
                    array_push($btns, [array("text"=>$value->name,"callback_data"=>$pre.$value->slug)]);
                }
            $response = $client->sendMessage([
                'chat_id' => $update->callback_query->message->chat->id,
                'text' => 'لیست گروه '.$dizin[1].'<a href="'.$imgroup.'"> </a>',
                'parse_mode'=>'HTML',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $btns
                    ])
                ]);
        }elseif (strpos(strtolower($dastor), 'dizi;') === 0) {
            $dizin = explode(';', $dastor);
            $btns = [];
            foreach ($json_o->{$dizin[1]}->dizi as $value) {
                    array_push($btns, [(array)$value]);
                }
            $response = $client->sendMessage([
                'chat_id' => $update->callback_query->message->chat->id,
                'text' => 'سریال های مربوط به شبکه '.$json_o->{$dizin[1]}->normal->name,
                'reply_markup' => json_encode([
                    'inline_keyboard' => $btns
                    ])
                ]);
        }elseif ((strpos(strtolower($dastor), 'unl;') === 0) || (strpos(strtolower($dastor), 'gon;') === 0)) {
            $dizin = explode(';', $dastor);
            if ($dizin[0] == 'unl') {
                $pre = 'unluler';
            }else{
                $pre = 'gonulluler';
            }
            foreach ($json_surv->$pre as $value) {
                if ($value->slug == $dizin[1]) {
                    $name = $value->name;
                    $instagram = $value->instagram;
                    $image = $value->image;
                    $biotr = $value->biotr;
                    $biofa = $value->biofa;
                    $slugn = $value->slug;
                }
            }
            $vvv = $name."\n".$biotr."\n".$biofa.'<a href="'.$image.'"> </a>';
            $response = $client->sendMessage([
                'chat_id' => $update->callback_query->message->chat->id,
                'text' => $vvv,
                'parse_mode'=>'HTML'
                // 'reply_markup' => json_encode([
                //     'inline_keyboard' => [
                //             [
                //                 ['text' => 'دریافت تصاویر '.$name,'callback_data'=>'instap;'.$instagram]
                //             ],
                //             [
                //                 ['text' => 'دریافت فیلم های '.$name,'callback_data'=>'instav;'.$instagram]
                //             ],
                //             [
                //                 ['text' => 'آمار و امتیازات '.$name,'callback_data'=>'stat;'.$slugn]
                //             ]
                //         ]
                //     ])
                ]);
        }elseif (strpos(strtolower($dastor), 'program;') === 0) {
            $dizin = explode(';', $dastor);
            $btns = [];
            foreach ($json_o->{$dizin[1]}->program as $value) {
                    array_push($btns, [(array)$value]);
                }
            $response = $client->sendMessage([
                'chat_id' => $update->callback_query->message->chat->id,
                'text' => 'برنامه های مربوط به شبکه '.$json_o->{$dizin[1]}->normal->name,
                'reply_markup' => json_encode([
                    'inline_keyboard' => $btns
                    ])
                ]);
        }elseif (strpos(strtolower($dastor), 'instap;') === 0) {
            $dizin = explode(';', $dastor);
            $media = Bolandish\Instagram::getMediaByHashtag($dizin[1], 6);
            foreach($media as $value){
              if ($value->dimensions->width === $value->dimensions->height){
                    $url = trim(strtok($value->display_src, '?')); 
                    $response = $client->sendPhoto([
                        'chat_id'=> $update->callback_query->message->chat->id,
                        'photo'=>fopen($url,'r'),
                        'caption'=>substr($value->caption, 0,190)
                        ]);
              }
            }
        }elseif ($dastor == 'kara-sevda') {
            $media = Bolandish\Instagram::getMediaByHashtag("benimkizim", 15);
            foreach($media as $value){
                if ($value->is_video && ($value->likes->count > 10)) {
                    $url = $value->video_url; 
                    $response = $client->sendVideo([
                        'chat_id'=> $update->callback_query->message->chat->id,
                        'video'=>fopen($url,'r'),
                        'caption'=>"کانال: @TurkTv \n ربات : @TurkTvBot"
                        ]);
                }elseif($value->likes->count > 10){
                    $url = trim(strtok($value->display_src, '?')); 
                    $response = $client->sendPhoto([
                        'chat_id'=> $update->callback_query->message->chat->id,
                        'photo'=>fopen($url,'r'),
                        'caption'=>"کانال: @TurkTv \n ربات : @TurkTvBot"
                        ]);
                }
                // $quu = array(
                //   "q" => "#benimkızım",
                //   "result_type"=> "recent",
                //   "count"=>5
                // );
                // $results = $connection->get('search/tweets', $quu);
                // foreach ($results->statuses as $twit) {
                //     $response = simpleTextSend($update->callback_query->message->chat->id,$twit->text);
                // }
            }
            $response = $client->sendMessage([
                'chat_id' => $update->callback_query->message->chat->id,
                'text' => 'دوست دارید تصاویر بیشتری دریافت کنید؟',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                            [
                                ['text' => 'بله','callback_data'=>'kara-sevda']
                            ],
                            [
                                ['text'=>'خیر','callback_data'=>'kheir']
                            ]
                        ]
                    ])
                ]);
        }else{
            $text = 'در حال تکمیل این قسمت هستیم لطفا بعدا امتحان کنید در صورت بروز اشکال به @alo_survivor اطلاع بدید';
            simpleTextSend($update->callback_query->message->chat->id,$text);
        }      
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
                'text' => "لیست دیگر کانالها \n Tv360 : /tv360 \n در حال حاضر فقط کانالهای پرطرفدار به لیست اضافه شده است. اگر کانال مورد علاقه شما در این لیست موجود نیست  از طریق اکانت @alo_survivor نام کانال را برای ما ارسال کنید تا به این لیست اضافه کنیم"
            ]);
        } else {
            $arri = listbarnameha($kanaln);
            $response = $client->sendMessage([
                'chat_id' => $update->message->chat->id,
                'text' => $arri,
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                            [
                                ['text' => 'دریافت فرکانس شبکه','callback_data'=>'ferekans;'.$kanaln]
                            ],
                            [
                                ['text' => 'سریالهای این شبکه','callback_data' => 'dizi;'.$kanaln]
                            ],
                            [
                                ['text' => 'برنامه های این شبکه','callback_data' => 'program;'.$kanaln]
                            ]
                        ]
                    ])

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
            'audio' => $update->message->audio->file_id
            ]);

        // simpleTextSend($update->message->chat->id,json_encode($response));

    }
    elseif ($update->message->video && ($update->message->chat->username == 'Mohammad6006')) {
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => 'fileid:'.$update->message->video->file_id.' width: '.$update->message->video->width.' height: '.$update->message->video->height.' duration: '.$update->message->video->duration.' mime_type: '.$update->message->video->mime_type.' file_size: '.$update->message->video->file_size
            ]);
    }
    elseif ($update->message->document && ($update->message->chat->username == 'Mohammad6006')) {
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => 'fileid:'.$update->message->document->file_id.' mime_type: '.$update->message->document->mime_type.' file_size: '.$update->message->document->file_size
            ]);
    }

    elseif ($update->message->reply_to_message->video && ($update->message->chat->username == 'Mohammad6006' || $update->message->chat->username == 'Saahraa54')) {
        if ($update->message->text == '@turktv' || $update->message->text == '@canli') {
            $response = $client->sendVideo([
                'chat_id' => $update->message->text,
                'video' => $update->message->reply_to_message->video->file_id,
                'caption' => $update->message->reply_to_message->caption,
                'duration' => $update->message->reply_to_message->video->duration,
                'width' => $update->message->reply_to_message->video->width,
                'height' => $update->message->reply_to_message->video->height
                ]);            
        }else{
            $response = $client->sendVideo([
                'chat_id' => $update->message->reply_to_message->chat->id,
                'video' => $update->message->reply_to_message->video->file_id,
                'caption' => $update->message->text,
                'duration' => $update->message->reply_to_message->video->duration,
                'width' => $update->message->reply_to_message->video->width,
                'height' => $update->message->reply_to_message->video->height
                ]);            
        }
    }
    elseif ($update->message->reply_to_message->document && ($update->message->chat->username == 'Mohammad6006' || $update->message->chat->username == 'Saahraa54')) {
        if ($update->message->text == '@turktv' || $update->message->text == '@canli') {
            $response = $client->sendDocument([
                'chat_id' => $update->message->text,
                'document' => $update->message->reply_to_message->document->file_id,
                'caption' => $update->message->reply_to_message->caption
                ]);            
        }else{
            $response = $client->sendDocument([
                'chat_id' => $update->message->reply_to_message->chat->id,
                'document' => $update->message->reply_to_message->document->file_id,
                'caption' => $update->message->text
                ]);            
        }
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
