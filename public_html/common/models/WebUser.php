<?php
namespace common\models;

use Yii;

/**
 * Extended yii\web\User
 *
 * This allows us to do "Yii::$app->user->something" by adding getters
 * like "public function getSomething()"
 *
 * So we can use variables and functions directly in `Yii::$app->user`
 */
class WebUser extends \yii\web\User {

    public function getFullName(){
        return "{$this->getLasttName()} {$this->getFirstName()} {$this->getPatronymic()}";
    }

    public function getFirstName(){
        return $this->identity->first_name;
    }

    public function getLasttName(){
        return $this->identity->last_name;
    }

    public function getPatronymic(){
        return $this->identity->patronymic;
    }

    public function getDefaultCountry(){
        return Country::findOne([
            'domain' => Yii::$app->params['domain']
        ]);
    }

    public function getDefaultLanguage(){
        return Language::findOne([
            'is_default' => true
        ]);
    }
}