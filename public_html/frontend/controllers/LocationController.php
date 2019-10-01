<?php
namespace frontend\controllers;

use common\models\Language;
use common\models\Region;
use common\models\City;
use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\HttpException;


class LocationController extends BaseController
{
    /**
     * Текущая локация
     */
    protected $location = null;
    protected $location_domains = [
        'country' => null,
        'region'  => null,
        'city'    => null,
    ];
    public $params;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function actionVyborGoroda(){

        $cms = \common\models\Cms::getByTechname('location');
        $this->setPageTitle($cms->_text->seo_title);
        Yii::$app->view->params['seo_h1'] = $cms->_text->seo_h1;
        Yii::$app->view->params['seo_desc'] = $cms->_text->seo_desc;
        Yii::$app->view->params['seo_keywords'] = $cms->_text->seo_keywords;
        $regions = (new Query())
            ->select('*, regions.id as id')
            ->from('regions')
            ->leftJoin('regions_text', 'regions_text.regions_id = regions.id')
            ->where(['countries_id' => Yii::$app->location->country->id])
            ->andWhere(['regions_text.languages_id' => Yii::$app->location->country->languages_id])
            ->orderBy(['name' => SORT_ASC])
            ->all();
        Yii::$app->view->params['h1'] = __('_Location');
        return $this->render('list',  [
            'regions'      => $regions,
        ]);
    }

    public function actionSelectLocation(){
        $domain = Yii::$app->request->get('domain');
        if($domain != 'reset') {
            $location = Region::find()->where(['domain' => $domain])->withText()->one();
            if (!$location) {
                $location = City::find()->where(['domain' => $domain])->withText()->one();
                if (!$location) {
                    throw new HttpException(404, 'Not Found');
                }
                $this->location_domains['city'] = $domain;
                $this->location_domains['region'] = $location->region->domain;
                $this->location_domains['country'] = $location->region->country->domain;
                Yii::$app->location->city = $location;
                Yii::$app->location->region = $location->region;
                Yii::$app->location->country = $location->region->country;
            }else{
                $this->location_domains['city'] = null;
                $this->location_domains['region'] = $location->domain;
                $this->location_domains['country'] = $location->country->domain;
                Yii::$app->location->region = $location;
                Yii::$app->location->country = $location->country;
            }

            $_COOKIE["city"] = $this->location_domains['city'];
            $_COOKIE["region"] = $this->location_domains['region'];
            $_COOKIE["country"] = $this->location_domains['country'];
        }else{
            $_COOKIE["city"] = $this->location_domains['city'];
            $_COOKIE["region"] = $this->location_domains['region'];
            $_COOKIE["country"] = $this->location_domains['country'];
            return $this->redirect(Url::toRoute("/"));
        }

        return $this->redirect(Url::toRoute("/${domain}/"));
    }
}