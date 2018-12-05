<?php
namespace frontend\rules\url;

use common\models\Language;
use common\models\Region;
use yii;
use yii\helpers\ArrayHelper;
use yii\web\UrlRule;
use yii\web\UrlRuleInterface;

class LocationCategoryUrlRule extends UrlRule implements UrlRuleInterface
{

    public $categoryRoute = 'categories/index';

    public $categoryOnlyRoute = 'categories/only';

    protected function normalizeUrlForLocation($url){
        if (substr($url, -1) !== '/') {
            $url = "$url/";
        }

        if (Yii::$app->location->city){
            $url = $url . Yii::$app->location->city->domain;
        } else {
            $url = $url;
        }

        return trim($url, '/');
    }

    protected function setParams($params){
       $urlSections = Yii::$app->request->get();
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();

        $result = parent::parseRequest($manager, $request);

        if ($result === false) {
            return false;
        }

        list($route, $params) = $result;
//        print_r($result);exit;
        if (isset($params['placement']) && !$this->isValidPlacement($params)) {
            return false;
        }

        if (! $this->isValidCategory($params)){
            return false;
        }

        if ($params['city'] && !$this->isValidLocation($params)){
            return false;
        }

        return [$route,$params];
    }

    public function isValidPlacement($params){
        $placementName = ArrayHelper::getValue($params, 'placement', false);

        $placement = \common\models\Placement::find()
                        ->seoUrl($placementName)
                        ->one();

        if ($placement) {
            \common\models\Placement::setCurrent($placement);
        }

        return $placement;
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
    private function isValidLocation($params){
        $cityName = ArrayHelper::getValue($params, 'city', false);
        $city = \common\models\City::find()
                        ->byLocation()
                        ->whereDomain($cityName)
                        ->one();
        if($city){
            Yii::$app->location->city = $city;
            return $city;
        }else{
            $region = Region::find()->withText(['languages_id' => Language::getDefault()->id])
                ->where(['domain' => $cityName])
                ->one();
            if($region){
                return $region;
            }
        }
        return false;
    }
}
