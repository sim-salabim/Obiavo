<?php
namespace frontend\components;

use yii;
use yii\base\Object;
use common\models\Country;

/**
 * Компонент для работы с локациями
 * Здесь будет храниться текущая локация
 *
 * обращение к компоненту Yii::$app->location
 *
 * Установка локации :
 *         - Для города Yii::$app->location->city = (\common\models\City) $object;
 *         - Для страны Yii::$app->location->country = (\common\models\Country) $object;
 */

class Location extends Object {

    /**
     * Здесь будет храниться один из объектов локации
     */
    private $_locationObject = null;

    /**
     * Запоминаем страну и город(если выбран)
     */
    private $_city = null;
    private $_country = null;


    public function init(){

        if(!$this->country) {
            $country = Country::find()->current()->one();
            $this->country = $country ? $country : Country::find()->one();
        }
    }

    public function getDomain(){
        return $this->country->domain;
    }

    public function setCity($city){

        if (! $city) return;

        $this->_city = $city;
        $this->_locationObject = $city;
    }

    public function getCity(){

        return $this->_city;
    }

    public function setCountry($country){
        $this->_country = $country;

        /**
         * Данные о локации устанавливаем из страны только в том случае. если не указан город
         */
        if (! $this->city)
            $this->_locationObject = $country;
    }

    public function getCountry(){

        return $this->_locationObject;
    }

    //--------- Свойства для вывода данных о локации ---

    public function getName(){

        return $this->_locationObject->_text->name;
    }

    public function getName_rp(){

        return $this->_locationObject->_text->name_rp;
    }

    public function getName_pp(){

        return $this->_locationObject->_text->name_pp;
    }
}