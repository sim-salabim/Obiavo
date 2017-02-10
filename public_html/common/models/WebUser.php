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

    /**
     * @var object \common\models\Language
     */
    private $language = null;

    private $country = null;

    /**
     * Получить текущий язык
     * @return type
     */
    public function getLanguage(){
        if (!$this->language){
            $this->language = Yii::$app->location->country->language;//Language::getLanguageDeafault();
        }

        return $this->language;
    }
}