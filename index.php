<?php
require 'InstagramDownload.class.php';
require 'vendor/autoload.php';
use PHPHtmlParser\Dom;

$token = getenv('acstok');
$client = Zelenin\Telegram\Bot\ApiFactory::create($token);
$update = json_decode(file_get_contents('php://input'));

function listbarnameha($kanal)
{
    $dom = new Dom;
    $dom->load('http://www.tvyayinakisi.com/'.$kanal);
    $html = $dom->outerHtml;
    $roztime = $dom->find('span[class=date]')[0];
    $btimes = $dom->find('div[class=two columns time]');
    $progtitles = $dom->find('div[class=ten columns]');
    $arri = '';
    foreach ($btimes as $key => $btime) {
        $arri .= $btime->text.":".$progtitles[$key]->text."\n";
    }
    return $arri;
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
try {

    if($update->message->text == '/contact')
    {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => "کانال تلگرام مرتبط با این ربات : @TurkTV \n در صورتی که مشکل در کار با این ربات داشتید برای گزارش و ارسال پیام به برنامه نویس و تهیه کننده این ربات از طریق اکانت @alo_survivor در ارتباط باشید "
        ]);
    }
    elseif (strpos(strtolower($update->message->text), '/kanal') !== false ) {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $kanalname = explode('-', $update->message->text);
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
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => "کانال دلخواه خود را از لیست زیر انتخاب کنید \n در صورتی که کانال مورد نظر شما در لیست موجود نمیباشد گزینه دیگر کانالها را انتخاب کنید.",
            'reply_markup' => json_encode([
                    'keyboard'=> [
                        ['/kanal-Tv8','/kanal-StarTv','/kanal-D'],
                        ['/kanal-ShowTv','/kanal-ATV','/kanal-TRT1'],
                        ['/kanal-7','/kanal-Tv2','/kanal-FOX'],
                        ['/kanal-دیگر کانالها'],
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ]),
            ]);

    }
    else
    {
        $daryafti = 'Not detect';
        $daryafti = $update->message->text;
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
        $response = $client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text' => 'دستور ارسالی شما در این ربات تعریف نشده است برای ارسال گزارش مشکل خود به اکانت @alo_survivor پیام خود را ارسال کنید'
            ]);
    }

} catch (\Zelenin\Telegram\Bot\NotOkException $e) {


}
