<?php
namespace common\models;

use common\models\libraries\TelegrammLoging;
use Exception;
use frontend\components\Location;
use Yii;

class Mailer {

    /**
     * @param $send_to, адресат
     * @param $subject
     * @param $template, представление (common/mail)
     * @param $arr, ассоциативный массив с параметрами
     * @param $from, ассоциативный массив ['email' => 'example@mail.ru', 'name' => 'example_name']
     * @param $attachement, array <File>
     */
    public static function send($send_to, $subject, $template, $arr, $from = null){
        $url = Yii::$app->request->url;
        TelegrammLoging::send("Url: ".$url);
        switch(Location::getCurrentDomain()){
            case "obiavo.ru" :
                $from_arr = ['robot@obiavo.site' => Yii::$app->name];
                break;
            case "obiavo.by" :
                $from_arr = ['robot@obiavo.by' => Yii::$app->name];
                break;
            case "obiavo.kz" :
                $from_arr = ['robot@obiavo.kz' => Yii::$app->name];
                break;
            case "obiavo.uz" :
                $from_arr = ['robot@obiavo.uz' => Yii::$app->name];
                break;
            case "obiavo.su" :
                $from_arr = ['robot@obiavo.su' => Yii::$app->name];
                break;
        }

        if($from){
            $from_arr = [$from['email']  => $from['name']];
        }
        TelegrammLoging::send("From arr: ".json_encode($from_arr));
        try {
            Yii::$app
                ->mailer
                ->compose(
                    ['html' => $template],
                    $arr
                )
                ->setFrom($from_arr)
                ->setTo($send_to)
                ->setSubject($subject)
                ->send();
        }catch(Exception $e){
            TelegrammLoging::send("Mailer send exception: ".$e->getMessage());
        }
    }
}