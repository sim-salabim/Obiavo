<?php
namespace frontend\helpers;

use Yii;

class LocationHelper {

    /** Преобразует строку урла в соответствии с выбранной локацией
     * @param $url
     */
    public static function getDomainForUrl($url){
        $location = Yii::$app->location;
        $domain = '';
        if($location->city){
            $domain = $location->city->domain."/";
        }else{
            if($location->region) $domain = $location->region->domain."/";
        }
        return $url . $domain;
    }
}
