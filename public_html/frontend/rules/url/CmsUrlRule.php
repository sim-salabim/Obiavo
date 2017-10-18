<?php

namespace frontend\rules\url;

use common\models\Cms;
use yii\web\UrlRule;
use yii\helpers\ArrayHelper;

class CmsUrlRule extends UrlRule
{
    public $connectionID = 'db';

    public function parseRequest($manager, $request)
    {
        $result = parent::parseRequest($manager, $request);
        list($route, $params) = $result;
        if ($result === false OR $route != 'cms/view' OR $result === false) {
            return false;
        }
        $cms_url = ArrayHelper::getValue($params, 'cmsUrl', false);
        $cms = Cms::find()->leftJoin('cms_text', '`cms_text`.`cms_id` = `cms`.`id`')->where(['cms_text.url' => $cms_url])->one();
        if (!$cms){
            return false;
        }
        return [$route,$params];
    }
}
