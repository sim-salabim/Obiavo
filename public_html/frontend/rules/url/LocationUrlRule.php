<?php
namespace frontend\rules\url;

use yii;
use yii\web\UrlRuleInterface;
use yii\base\Object;
use yii\web\UrlRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class LocationUrlRule extends UrlRule implements UrlRuleInterface
{

    protected static $url = '';
    protected static $templateUrl = '';

    public function createUrl($manager, $route, $params) {
        if ($route === 'city/generate-url'){
            return $this->generateUrlCity($params);
        }

        return false;
    }

    public function parseRequest($manager, $request)
    {
        $result = parent::parseRequest($manager, $request);

        if ($result === false) {
            return false;
        }

        list($route, $params) = $result;

        $domain = ArrayHelper::getValue($params, 'domain', false);

        $city = \common\models\City::find()
                        ->byLocation()
                        ->whereDomain($domain)
                        ->one();

        if (!$city){
            $region = \common\models\Region::findOne(['domain' => $domain]);
            if(!$region) return false;
        }

        return [$route,$params];
    }


    /**
     * Генерация ссылки для города
     * Значения $params
     * cityDomain - для какого города сгенерировать ссылку
     * url        - url, из которого будет формироваться ссылка (по умолчанию берется текущий)
     *
     * Алгоритм:
     * 1. Сравниваем переданный url с текущим ($url property), при их неравенстве, обновляем рабочий url
     *    и устанавливаем шаблон ссылки для удобной замены домена
     * 2. С помощью шаблона получаем готовую ссылку, путем замены якоря {city} на домен
     */
     protected function generateUrlCity($params){
        if (!isset($params['cityDomain'])) return false;

        $url = isset($params['url']) ? $params['url'] : \Yii::$app->request->url;

        if ($url !== self::$url ){
            self::$url = $url;
            $this->generateTemplateUrl($params);
        }

        return $this->getCityUrl($params);
    }

    protected function generateTemplateUrl($params){
        $isCity = function($cityDomain){
            if (empty($cityDomain)) return false;

            return \common\models\City::findOne(['domain' => $cityDomain]);
        };

        $url = self::$url;
        $cityDomain = $params['cityDomain'];

        $href = parse_url($url, PHP_URL_PATH);

        $paths = explode("/", $href);

        $endPath = $paths[count($paths)-1];

        if ($isCity($endPath)) {

            $newHref = str_replace($endPath, '{city}', $href);

        } elseif(count($paths) > 1 && substr($href, -1) !== '/'){
            $newHref = "$href/{city}";
        }

        self::$templateUrl = empty($newHref) ? $url : str_replace($href, $newHref, $url);
    }

    protected function getCityUrl($params){
        if (strripos(self::$templateUrl, '{city}')){
            return str_replace('{city}', $params['cityDomain'], self::$templateUrl);
        }

        return self::$templateUrl . $params['cityDomain'];
    }

}
