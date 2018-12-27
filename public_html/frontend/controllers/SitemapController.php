<?php
namespace frontend\controllers;

use common\models\Category;
use common\models\CategoryPlacement;
use common\models\City;
use common\models\Placement;
use common\models\SitemapIndex;
use yii\web\HttpException;
use Yii;


class SitemapController extends BaseController
{
    public $layout=false;
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        $links = SitemapIndex::find()->where(['countries_id' => Yii::$app->location->country->id])->all();
        return $this->renderPartial('index', compact('links'));
    }

    public function actionShowIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        $current_domain = Yii::$app->location->country->domain;
        $current_domain = str_replace("obiavo.", "", $current_domain);
        return $this->renderPartial('sitemap.'.$current_domain.'.xml');
    }

    /**
     * Выводит xml сайтмапа для индексного урла типа /sitemap27.city_5_3.xml
     * @return string
     */
    public function actionCity()
    {
        $current_domain = Yii::$app->location->country->domain;
        $current_index = Yii::$app->request->get('index');
        $city_offset = Yii::$app->request->get('city');
        $tmp_arr = explode("_", $city_offset);
        $city_id = $tmp_arr[0];
        $offset = $tmp_arr[1] * 10000;
        $city = City::find()->where(['id'=>$city_id])->one();
        $sm_index = SitemapIndex::find()->where(['link'=>'https://'.$current_domain.'/sitemap'.$current_index.'.city_'.$tmp_arr[0].'_'.$tmp_arr[1].'.xml'])->one();
        if(!$sm_index){
            throw new HttpException(404, 'Not Found');
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        $categories = Yii::$app->cache->getOrSet("category-offset-".$offset,
            function () use (
                $offset
            ) {
                return Category::find()->where(['active'=>1])->limit(10000)->offset($offset)->all();
        }, 8640000);
        return $this->renderPartial('city', compact('categories', 'city', 'current_domain'));
    }

    public function actionPlacementCity()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        $current_domain = Yii::$app->location->country->domain;
        $city = City::find()->where(["id"=>Yii::$app->request->get('city')])->one();
        if(!$city) throw new HttpException(404, 'Not Found');
        $city_domain = $city->domain;
        $current_offset = Yii::$app->request->get('offset');
        $offset = $current_offset * 1000;
        $links = Yii::$app->cache->getOrSet("category-placement-".$offset,
            function () use (
                $offset
            ) {
                return CategoryPlacement::find()->limit(1000)->offset($offset)->all();
            }, 8640000);
        return $this->renderPartial('placement-city', compact('links', 'city_domain', 'current_domain'));
    }

    public function actionPlacement()
    {
        $current_domain = Yii::$app->location->country->domain;
        $current_index = Yii::$app->request->get('index');
        $current_offset = Yii::$app->request->get('offset');
        $sm_index = SitemapIndex::find()->where(['link'=>'https://'.$current_domain.'/sitemap'.$current_index.'.catpl_'.$current_offset.'.xml'])->one();
        if(!$sm_index) throw new HttpException(404, 'Not Found');
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        $offset = $current_offset * 1000;
        $links = Yii::$app->cache->getOrSet("category-placement-".$offset,
            function () use (
                $offset
            ) {
            return CategoryPlacement::find()->limit(1000)->offset($offset)->all();
            }, 8640000);
        return $this->renderPartial('placement', compact('links', 'current_domain'));
    }

    /**
     * ВЫводит xml сайтмапа для индексного урла типа /sitemap1.xml
     *
     * @return string
     * @throws HttpException
     */
    public function actionIdx()
    {
        $current_domain = Yii::$app->location->country->domain;
        $current_index = Yii::$app->request->get('index');
        $sm_index = SitemapIndex::find()->where(['link'=>'https://'.$current_domain.'/sitemap'.$current_index.'.xml'])->one();
        if(!$sm_index) throw new HttpException(404, 'Not Found');
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        $offset = --$current_index * 10000;
        $categories = Yii::$app->cache->getOrSet("category-offset-".$offset,
            function () use (
                $offset
            ) {
                return Category::find()->where(['active'=>1])->limit(10000)->offset($offset)->all();
            }, 8640000);
        return $this->renderPartial('idx', compact('categories', 'current_domain'));
    }

}
