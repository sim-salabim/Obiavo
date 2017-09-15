<?php
namespace frontend\rules\url;

use yii\web\UrlRuleInterface;
use yii\web\UrlRule;

class SelectLocationUrlRule extends UrlRule implements UrlRuleInterface
{

    public function parseRequest($manager, $request)
    {
        $result = parent::parseRequest($manager, $request);

        if ($result === false OR $result[0] != 'location/select-location') {
            return false;
        }
        list($route, $params) = $result;
        return [$route,$params];
    }
}
