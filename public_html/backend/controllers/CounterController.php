<?php
namespace backend\controllers;

use common\models\City;
use common\models\CounterCategory;
use common\models\CounterCityCategory;
use common\models\Country;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class CounterController extends Controller
{

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

    public function actionIndex(){
        $countries = Country::find()->where(['active' => 1])->all();
        return $this->render('index', compact('countries'));
    }

    public function actionCity(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $this->checkCredentials();
        $count = (new Query())
            ->select('cities_id, COUNT( * ) as ads_amount')->from('ads')
            ->where(['active' => 1])
            ->andWhere(['in', 'cities_id', (new Query())->select('cities_id')->from('cities_order')->all()])
            ->groupBy('cities_id')
            ->all();
        try {
            foreach ($count as $item) {
                $city = City::find()->where(['id' => $item['cities_id']])->one();
                $city->ads_amount = $item['ads_amount'];
                $city->save();
            }
        }catch(\Exception $e){
            Yii::$app->response->statusCode = 500;
            Yii::$app->response->statusText = $e->getMessage();
            return ['status'=>500, 'message' => $e->getMessage()];
        }
        Yii::$app->response->statusCode = 200;
        return ['status'=>200, 'message' => 'success'];
    }

    public function actionCategory(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $this->checkCredentials();
        $country_id = Yii::$app->request->get('country');
        if(!$country_id) throw new NotFoundHttpException();
        $country = Country::find()->where(['id' => $country_id, 'active' => 1])->one();
        if(!$country) throw new NotFoundHttpException();
        try {
            $count = (new Query())
                ->select('categories_id, COUNT( * ) as ads_amount')->from('ads')
                ->where(['active' => 1])
                ->andWhere(['in', 'cities_id',
                    (new \yii\db\Query())
                        ->select(['id'])
                        ->from('cities')
                        ->groupBy(['id'])
                        ->where(['in', 'regions_id',
                            (new \yii\db\Query())->select('id')->from('regions')->where(['countries_id' => $country_id])])])
                ->groupBy('categories_id')
                ->all();
            $array = [];
            foreach ($count as $c) {
                $array[$c['categories_id']] = $c['ads_amount'];
            }
            $count_add = (new Query())->select('ads_has_categories.categories_id categories_id, COUNT( * ) as ads_amount')->from('ads_has_categories')
                ->leftJoin('ads', 'ads_has_categories.ads_id = ads.id')
                ->where(['in', 'ads.cities_id',
                    (new \yii\db\Query())
                        ->select(['id'])
                        ->from('cities')
                        ->groupBy(['id'])
                        ->where(['in', 'regions_id',
                            (new \yii\db\Query())->select('id')->from('regions')->where(['countries_id' => $country_id])])])
                ->andWhere(['ads.active' => 1])
                ->groupBy('ads_has_categories.categories_id')
                ->all();
            foreach ($count_add as $k => $c) {
                if (isset($array[$c['categories_id']])) {
                    $array[$c['categories_id']] = $array[$c['categories_id']] + $c['ads_amount'];
                } else {
                    $array[$c['categories_id']] = $c['ads_amount'];
                }
            }

            $existing_counter = CounterCategory::find()->where(['countries_id' => $country_id])->all();
            foreach ($existing_counter as $ec) {
                $ec->delete();
            }

            foreach ($array as $k => $v) {
                $counter = CounterCategory::find()->where([
                    'countries_id' => $country_id,
                    'categories_id' => $k
                ])->one();
                if (!$counter) {
                    $counter = new CounterCategory();
                    $counter->countries_id = $country_id;
                    $counter->categories_id = $k;
                }
                $counter->ads_amount = $v;
                $counter->save();
            }
        }catch(\Exception $e){
            Yii::$app->response->statusCode = 500;
            Yii::$app->response->statusText = $e->getMessage();
            return ['status'=>500, 'message' => $e->getMessage()];
        }
        Yii::$app->response->statusCode = 200;
        return ['status'=>200, 'message' => 'success'];
    }

    public function actionCityCategory(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $this->checkCredentials();
        try {
            $counter_city_categories = CounterCityCategory::find()->all();
            foreach ($counter_city_categories as $c) {
                $c->delete();
            }
            $cities = City::find()->where(['sitemap' => 1])->all();
            foreach ($cities as $city) {
                $count = (new Query())
                    ->select('categories_id, COUNT( * ) as ads_amount')->from('ads')
                    ->where(['active' => 1])
                    ->andWhere(['cities_id' => $city->id])
                    ->groupBy('categories_id')
                    ->all();
                foreach ($count as $c) {
                    $counter = new CounterCityCategory();
                    $counter->categories_id = $c['categories_id'];
                    $counter->cities_id = $city->id;
                    $counter->ads_amount = $c['ads_amount'];
                    $counter->save();
                }
            }
        }catch(\Exception $e){
            Yii::$app->response->statusCode = 500;
            Yii::$app->response->statusText = $e->getMessage();
            return ['status'=>500, 'message' => $e->getMessage()];
        }
        Yii::$app->response->statusCode = 200;
        return ['status'=>200, 'message' => 'success'];
    }

    /**
     * Проверяем предоставленный токен если кто-то вдруг решит постучаться по этому урлу без разрешения
     *
     * @throws ForbiddenHttpException
     */
    private function checkCredentials(){
        $token = Yii::$app->request->get('token');
        if(!$token OR $token != Yii::$app->params['cron_token']){
            $error = (object) array();
            $error->error_code = '403';
            $error->error_msg = 'Неверный токен';
            throw new ForbiddenHttpException();
        }
    }
}