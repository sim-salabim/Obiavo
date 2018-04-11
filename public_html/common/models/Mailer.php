<?php
namespace common\models;

use Yii;

class Mailer {

    /**
     * @param $send_to, адресат
     * @param $subject
     * @param $template, представление (common/mail)
     * @param $arr, ассоциативный массив с параметрами
     * @param $from, ассоциативный массив ['email' => 'example@mail.ru', 'name' => 'example_name']
     */
    public static function send($send_to, $subject, $template, $arr, $from = null){
        $from_arr = [Yii::$app->params['commonAdminEmail'] => Yii::$app->name];
        if($from){
            $from_arr = [$from['email']  => $from['name']];
        }
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
    }
}