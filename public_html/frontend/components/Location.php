<?php
namespace frontend\components;

use yii;
use yii\base\Object;
use yii\base\Component;
use common\models\Country;
use common\models\Language;

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

class Location extends Component {

    /**
     * Здесь будет храниться один из объектов локации
     */
    private $_locationObject = null;

    /**
     * Запоминаем страну и город(если выбран)
     */
    private $_city = null;
    private $_country = null;
    // Текущий язык системы
    public $_language = null;


    public function init(){
        parent::init();

        if(!$this->_country) {
            $country = Country::find()->current()->one();
            $this->country = $country ? $country : Country::find()->one();
        }

//        $lang = $this->country->language;

//        $this->language = $lang ? $lang : \common\models\Language::find()->isDefault()->one();
    }

    public function getCountry(){
        if ($this->_country){
            return $this->_country;
        }

        $country = Country::find()->current()->one();
        $this->country = !empty($country) ? $country : Country::find()->one();

        return $this->_country;
    }

    public function setCountry($country){
        $this->_country = $country;

        /**
         * Данные о локации устанавливаем из страны только в том случае. если не указан город
         */
        if (! $this->city)
            $this->_locationObject = $country;
    }

    public function getLanguage(){
        if ($this->_language) {
            return $this->_language;
        }

        $language =  $this->country->language;
        $this->_language = $language ? $language : \common\models\Language::find()->isDefault()->one();

        return $this->_language;
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