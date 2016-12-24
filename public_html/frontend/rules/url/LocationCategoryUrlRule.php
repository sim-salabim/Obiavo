<?php
namespace frontend\rules\url;

use yii;
use yii\web\UrlRuleInterface;
use yii\base\Object;
use yii\web\UrlRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class LocationCategoryUrlRule extends UrlRule implements UrlRuleInterface
{

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        
        $result = parent::parseRequest($manager, $request);
        
        if ($result === false) {
            return false;
        }

        list($route, $params) = $result;                    
        
        if (! $this->isValidCategory($params)) return false;
        
        if ($params['city'] && !$this->isValidCity($params)){
            return false;
        }
        
        return [$route,$params];                
    }
    
    private function isValidCategory($params){
        $categoryName = ArrayHelper::getValue($params, 'category', false);
        
        $category = \common\models\Category::find()
                            ->searchUrlByLanguage($categoryName)
                            ->one();        
        return $category;
    }
    
    /**
     * @return bool or \common\models\City $object
     */
    private function isValidCity($params){
        
        $cityName = ArrayHelper::getValue($params, 'city', false);
        
        $city = \common\models\City::find()                        
                        ->byLocation()
                        ->whereDomain($cityName)
                        ->one();     
        
        Yii::$app->location->city = $city;
        
        return \common\models\City::find()                        
                        ->byLocation()
                        ->whereDomain($cityName)
                        ->one();
    }
}
