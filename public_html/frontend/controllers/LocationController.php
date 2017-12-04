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

        $this->setPageTitle(__('_Location'));
        $regions = (new Query())->select('*')->from('regions')
            ->leftJoin('regions_text', 'regions_text.regions_id = regions.id')
            ->where(['countries_id' => Yii::$app->location->country->id])
            ->andWhere(['languages_id' => Yii::$app->location->country->languages_id])
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
            setcookie("country", $this->location_domains['country'], null, '/');
            setcookie("region", $this->location_domains['region'], null, '/');
            setcookie("city", $this->location_domains['city'], null, '/');
        }else{
            setcookie("country", $this->location_domains['country'], null, '/');
            setcookie("region", $this->location_domains['region'], null, '/');
            setcookie("city", $this->location_domains['city'], null, '/');
            return $this->redirect(Url::toRoute("/"));
        }

        return $this->redirect(Url::toRoute("/${domain}/"));
    }
}