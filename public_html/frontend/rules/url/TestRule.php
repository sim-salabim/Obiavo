<?php

namespace frontend\rules\url;

use common\models\Ads;
use common\models\Cms;
use common\models\SocialNetworksGroups;
use yii\web\UrlRule;
use yii\helpers\ArrayHelper;

class TestRule extends UrlRule
{
    public $connectionID = 'db';

    public function parseRequest($manager, $request)
    {
        $result = parent::parseRequest($manager, $request);
        list($route, $params) = $result;
        if ($result === false OR $route != 'ad/test-post' OR $result === false) {
            return false;
        }
        $ad_id = ArrayHelper::getValue($params, 'ad', false);
        $group_id = ArrayHelper::getValue($params, 'group', false);
        $ad = Ads::findOne($ad_id);
        if (!$ad){
            return false;
        }
        $group = SocialNetworksGroups::findOne($group_id);
        if (!$group){
            return false;
        }
        return [$route,$params];
    }
}