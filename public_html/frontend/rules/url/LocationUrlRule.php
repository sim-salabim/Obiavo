<?php
namespace frontend\rules\url;

use yii\web\UrlRuleInterface;
use yii\base\Object;
use yii\web\UrlRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class LocationUrlRule extends UrlRule implements UrlRuleInterface
{

    /**
     * @var string the redirect to url.
     */
    public $redirect;
    
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'car/index') {
            if (isset($params['manufacturer'], $params['model'])) {
                return $params['manufacturer'] . '/' . $params['model'];
            } elseif (isset($params['manufacturer'])) {
                return $params['manufacturer'];
            }
        }
        return false;  // this rule does not apply
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        
        $result = parent::parseRequest($manager, $request);
        
        if ($result === false) {
            return false;
        }

        list($route, $params) = $result;
        
        $locationName = ArrayHelper::getValue($params, 'locationName', false);
        
        $city = \common\models\City::find()                        
                        ->byLocation()
                        ->whereDomain($locationName)
                        ->one();        
        
        $params['url'] = str_replace(
                ['<locationName>'],
                [$locationName],
                $this->redirect
            );
        
        return [$route,$params];
        
        return false;  // this rule does not apply
    }
}
