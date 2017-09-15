<?php
namespace common\models;

use Yii;

class Mailer {

    /**
     * @param $send_to, адресат
     * @param $subject
     * @param $template, представление (common/mail)
     * @param $arr, ассоциативный массив с параметрами
     */
    public static function send($send_to, $subject, $template, $arr){
        Yii::$app
            ->mailer
            ->compose(
                ['html' => $template],
                $arr
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($send_to)
            ->setSubject($subject)
            ->send();
    }
}