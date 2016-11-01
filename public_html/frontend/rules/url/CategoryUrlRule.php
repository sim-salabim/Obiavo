<?php

namespace frontend\rules\url;

use yii\web\UrlRule;
use app\models\CategoryGenerated;
use app\models\City;
use yii\helpers\ArrayHelper;

class CategoryUrlRule extends UrlRule
{
    public $connectionID = 'db';

    public function parseRequest($manager, $request)
    {
        $patternIsValid = parent::parseRequest($manager, $request);

        if ($patternIsValid === false) {
            return false;
        }

        list($route, $params) = $patternIsValid;

        $categoryType = ArrayHelper::getValue($params, 'categoryType', false);
        $categoryAlias = ArrayHelper::getValue($params, 'categoryAlias', false);
        $citySelected = ArrayHelper::getValue($params, 'city', false);


        $category = CategoryGenerated::find()->select('url')->where(['url' => $categoryAlias])->one();

        if (!$category){
            return false;
        }

        if ($citySelected){
            $city = City::find()->select('domain')->where(['domain' => $citySelected])->one();

            if (!$city){
                return false;
            }
        }

        var_dump($category);die();
        
        $pathInfo = $request->getPathInfo();

        $fullUrlPath = parse_url($pathInfo)['path'];

        $allPathsInUrl = explode('/', $fullUrlPath);
        $route = Route::findRouteByUrl($pathInfo);

        foreach($allPathsInUrl as $urlPath){
            CategoryGenerated::find()->select('url')->where(['url' => $pathInfo])->one()->url;
        }

        die;
        if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches)) {

            $route = CategoryGenerated::find()->select('url')->where(['url' => $pathInfo])->one()->url;
            $params= array();

            if($route) {
                return [$route, $params];
            }

        }

        return false;  // это правило не подходит
    }
}
