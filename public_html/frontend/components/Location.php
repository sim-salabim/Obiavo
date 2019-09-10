<?php
namespace frontend\components;

use yii;
use yii\base\Object;
use yii\base\Component;
use common\models\Country;
use common\models\Region;
use common\models\City;
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
    private $_region = null;
    // Текущий язык системы
    public $_language = null;


    public function init(){
        parent::init();
        if(isset($_COOKIE['region'])){
            $region = Region::find()->where(['domain' => $_COOKIE['region']])->one();
            $this->_region = ($region) ? $region : null;
            if($this->_region){
                $this->_country = $this->_region->country;
            }
        }
        if(isset($_COOKIE['city'])){
            $city = City::find()->where(['domain' => $_COOKIE['city']])->one();
            $this->_city = ($city) ? $city : null;
            if($this->_city){
                $this->_region = $this->_city->region;
            }
        }
        if(!$this->_country) {
            if(!isset($_COOKIE['country'])) {
                $country = Country::find()->where(['domain' => $this->getCurrentDomain()])->one();
                setcookie("country", $country->domain, null, '/');
            }else{
                $country = Country::find()->where(['domain' => $this->getCurrentDomain()])->one();
            }
            $this->_country = $country ? $country : Country::find()->one();
        }else if($this->_country->domain != $_COOKIE['country']){
            $this->_country = Country::find()->where(['domain'=>$this->getCurrentDomain()])->one();
        }
//        $lang = $this->country->language;
//        $this->language = $lang ? $lang : Language::find()->isDefault()->one();
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
        $this->_region = $city->region;
        $this->_country = $city->region->country;
        $this->_locationObject = $city;
    }

    public function getCity(){

        return $this->_city;
    }

    public function setRegion($region){

        if (!$region) return;

        $this->_region = $region;
        $this->_country = $region->country;
        $this->_locationObject = $region;
    }

    public function getRegion(){

        return $this->_region;
    }

    //--------- Свойства для вывода данных о локации ---

    public function getName(){

        return $this->_locationObject->_text->name;
    }

    public function getName_rp(){

        return $this->_locationObject->_text->name_rp;
    }

    public function getName_pp(){
        if($this->_locationObject) {
            return $this->_locationObject->_text->name_pp;
        }else{
            return null;
        }
    }

    /**
     * Возвращает текущий домен
     * @return string
     */
    public static function getCurrentDomain(){
        $base_url = Yii::$app->request->getHostInfo();
        $domain = "obiavo.ru"; // сменой домена вот в этом месте меняется локация на локальном проекте, но на сервере должно быть всегда .ru
        if(strpos($base_url, 'obiavo.by') !== false){
            $domain = "obiavo.by";
        }
        if(strpos($base_url, 'obiavo.uz') !== false){
            $domain = "obiavo.uz";
        }
        if(strpos($base_url, 'obiavo.kz') !== false){
            $domain = "obiavo.kz";
        }
        if(strpos($base_url, 'obiavo.su') !== false){
            $domain = "obiavo.su";
        }
        if(strpos($base_url, 'obiavo.com') !== false){
            $domain = "obiavo.com";
        }
        if(strpos($base_url, 'obiavo.site') !== false){
            $domain = "obiavo.com";
        }
        if(strpos($base_url, 'obiavo.loc') !== false){
            $domain = "obiavo.com";
        }
        return $domain;
    }


    public static function getCurrentProtocol(){
        $base_url = Yii::$app->request->getHostInfo();
        $protocol = "http://";
        if(strpos($base_url, 'obiavo.by') !== false){
            $protocol = "https://";
        }
        if(strpos($base_url, 'obiavo.ru') !== false){
            $protocol = "https://";
        }
        if(strpos($base_url, 'obiavo.uz') !== false){
            $protocol = "https://";
        }
        if(strpos($base_url, 'obiavo.kz') !== false){
            $protocol = "https://";
        }
        if(strpos($base_url, 'obiavo.su') !== false){
            $protocol = "https://";
        }
        if(strpos($base_url, 'obiavo.com') !== false){
            $protocol = "https://";
        }
        if(strpos($base_url, 'obiavo.site') !== false){
            $protocol = "http://";
        }
        if(strpos($base_url, 'obiavo.loc') !== false){
            $protocol = "http://";
        }
        return $protocol;
    }
}