<?php

namespace frontend\rules\url;

use common\models\AddApplicationText;
use common\models\Ads;
use common\models\City;
use yii\web\UrlRule;

class NewAddCityUrlRule extends UrlRule
{
    public $connectionID = 'db';

    public function parseRequest($manager, $request)
    {
        $result = parent::parseRequest($manager, $request);
        list($route, $params) = $result;
        $item = null;
        if($params['url'] != Ads::DEFAULT_LINK) {
            $item = AddApplicationText::find()->where(['url' => $params['url']])->one();
            if ($result === false OR $route != 'ad/new-add' OR (!$item and strpos($params['url'], Ads::DEFAULT_LINK) === false)) {
                return false;
            }
        }
        if(isset($params['city']) and $params['city']){
            $city = City::find()->where(['domain'=>$params['city']])->one();
            if(!$city){
                return false;
            }
        }
        return [$route,$params];
    }
}