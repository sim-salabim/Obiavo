<?
namespace common\models\libraries;

class TelegrammLoging {

    public static function send($message){
        $chat_id = "88740047";
        $method = 'sendMessage';
        $token = '517180739:AAG_ZzuRtwArLMOeX7xEXYP9NXoEJIasPnk';
        $url = "https://api.telegram.org/bot$token/$method?text=$message&chat_id=$chat_id";
        file_get_contents($url);
    }
}