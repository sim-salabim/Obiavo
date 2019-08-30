<?php

namespace frontend\rules\url;

use common\models\Ads;
use yii\web\UrlRule;
use app\models\CategoryGenerated;
use app\models\City;
use yii\helpers\ArrayHelper;

class AdUrlRule extends UrlRule
{
    public $connectionID = 'db';

    public function parseRequest($manager, $request)
    {
        $result = parent::parseRequest($manager, $request);
        list($route, $params) = $result;
        if ($result === false OR $route != 'ad/view') {
            return false;
        }
        $ad_url = ArrayHelper::getValue($params, 'adUrl', false);
        $ad = Ads::find()->select('url')->where(['url' => $ad_url])->one();
        if (!$ad){
            return false;
        }
        return [$route,$params];
    }
}
