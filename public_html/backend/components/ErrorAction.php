<?php
/**
 * Компонент для полного ограничения доступа к админке только по авторизации
 */
namespace app\components;

use Yii;

class ErrorAction extends \yii\web\ErrorAction {

    public function run(){
        if (Yii::$app->user->isGuest){
            Yii::$app->getResponse()->redirect(Yii::$app->getUser()->loginUrl)->send();
            return;
        }

        return parent::run();
    }
}

