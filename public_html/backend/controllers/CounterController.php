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

            $counters = CounterCategory::find()->where(['countries_id' => $country_id])->all();
            foreach($counters as $c){
                $c->delete();
            }


            foreach($ids as $id){
                $id = $id['id'];
                $count = (new Query())
                    ->select('count(*) as count')
                    ->from('ads')
                    ->where(['active' => 1])
                    ->andWhere(['LIKE','categories_list', "|$id|"])
                    ->andWhere(['IN', 'cities_id',
                        (new Query())->select('id')->from('cities')->where(['IN', 'regions_id',
                            (new Query())->select('id')->from('regions')->where(['countries_id' => $country_id])
                        ])
                    ])
                    ->having('COUNT(*) > 1')
                    ->all();
                if(!empty($count)) {
                    $counter = new CounterCategory();
                    $counter->categories_id = $id;
                    $counter->countries_id = $country_id;
                    $counter->ads_amount = $count[0]['count'];
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