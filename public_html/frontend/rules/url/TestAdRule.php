<?php

namespace frontend\rules\url;

use common\models\Ads;
use common\models\Cms;
use yii\web\UrlRule;
use yii\helpers\ArrayHelper;

class TestAdRule extends UrlRule
{
    public $connectionID = 'db';

    public function parseRequest($manager, $request)
    {
        $result = parent::parseRequest($manager, $request);
        list($route, $params) = $result;
        if ($result === false OR $route != 'ad/test' OR $result === false) {
            return false;
        }
        $ad_id = ArrayHelper::getValue($params, 'ad', false);
        $ad = Ads::findOne($ad_id);
        if (!$ad){
            return false;
        }
        return [$route,$params];
    }
}