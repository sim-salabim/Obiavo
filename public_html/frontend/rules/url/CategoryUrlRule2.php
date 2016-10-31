<?php

namespace frontend\rules\url;

use yii\web\UrlRule;
use app\models\CategoryGenerated;

class CategoryUrlRule2 extends UrlRule
{
    public $connectionID = 'db';

    public function parseRequest($manager, $request)
    {
        $result = parent::parseRequest($manager, $request);
        var_dump($result);
        die;
        return false;
        return ['catalog/category',['param' => 'param1']];
        $pathInfo = $request->getPathInfo();

        $fullUrlPath = parse_url($pathInfo)['path'];

        $allPathsInUrl = explode('/', $fullUrlPath);
        $route = Route::findRouteByUrl($pathInfo);

        var_dump($route);
        var_dump(parse_url($pathInfo));die;
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


