<?php
namespace backend\controllers;

use common\models\CityOrder;
use common\models\Country;
use common\models\Language;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\City;
use comon\models\CityText;
use common\helpers\JsonData;
use yii\helpers\Url;

class CitiesController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actionIndex($region_id){
        $region = null;

        if ($region_id){
            $region = \common\models\Region::findOne($region_id);
            $cities = City::find()
                        ->withText()
                        ->where(['regions_id' => $region->id])
                        ->all();
        } else {
            $cities = City::find()->withText()->all();
        }

        return $this->render('index',  compact('region','cities'));
    }

    public function actionOrderCountryList(){
        $countries = Country::find()->withText()->all();

        return $this->render('order/index',  compact('countries'));
    }

    public function actionCityOrder($country_id){
        $cities = CityOrder::find()->where(['in','cities_id',
            (new \yii\db\Query())->select('id')->from('cities')->where(['in','regions_id',
                (new \yii\db\Query())->select('id')->from('regions')->where(['countries_id' => $country_id])
            ])])->orderBy(['cities_order.order' => SORT_ASC])
            ->all();

        $homeLink = ['label' => 'Настройка порядка вывода городов', 'url' => '/cities/order-country-list'];
        $country = Country::findOne($country_id);
        $breadcrumbs = \yii\widgets\Breadcrumbs::widget([
            'homeLink' => $homeLink,
            'links' => [$country->_text->name]
        ]);
        return $this->render('order/city-order',  compact('country_id', 'cities', 'breadcrumbs'));
    }

    public function actionSearchForOrdering(){
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = $post['query'];
        $country_id = (isset($post['country_id'])) ? $post['country_id'] : 1;// захардкодим пока так
        $cities = City::find()
            ->where(['not in', 'cities.id',
            (new \yii\db\Query())->select('cities_id')->from('cities_order')
        ])
            ->leftJoin('cities_text', 'cities_text.cities_id = cities.id')
            ->andWhere("cities_text.name LIKE '".$query."%'")
            ->andFilterWhere(['in', 'cities.regions_id',
                (new \yii\db\Query())->select('id')->from('regions')->where(['countries_id' => $country_id])])
            ->all();
        $result = [];
        foreach($cities as $city){
            $result[$city->_text->name] = array('id' => $city->id, 'text' => $city->_text->name, 'domain' => $city->domain);
        }
        return $result;
    }

    /** Используется для инпута с автокомплитом backend/widgets/views/form/search-autocomplete.php
     * @return array
     */
    public function actionSearch(){
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = $post['query'];
        $where_condition = [];
        if(isset($post['region_id']) and $post['region_id'] != ""){
            $where_condition = ['regions_id' => $post['region_id']];
        }
        $cities = City::find()
            ->leftJoin('cities_text', 'cities_text.cities_id = cities.id')
            ->where("cities_text.name LIKE '".$query."%'")
            ->andWhere($where_condition)
//            ->andFilterWhere(['in', 'cities.regions_id',
//                (new \yii\db\Query())->select('id')->from('regions')->where(['countries_id' => $country_id])])
            ->all();
        $result = [];
        foreach($cities as $city){
            $result[$city->id] = array('id' => $city->id, 'text' => $city->_text->name);
        }
        return $result;
    }

    public function actionSaveOrder(){
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $city_order_to_delete = CityOrder::find()->where(['in','cities_id',
            (new Query())->select('id')->from('cities')->where(['in', 'regions_id',
                (new Query())->select('id')->from('regions')->where(['countries_id' => $post['country_id']])
            ])]
            )->all();
        if(count($city_order_to_delete)){
            foreach ($city_order_to_delete as $row){
                $row->delete();
            }
        }

        if(count($post['city_order'])){
            foreach($post['city_order'] as $k => $r){
                $order = new CityOrder();
                $order->cities_id = $r;
                $order->order = $k;
                $order->save();
            }
        }
        return $this->redirect(Url::toRoute("cities/city-order?country_id=".$post['country_id']));
    }

    public function actionCreate($id){
        $city = new City;

        $toUrl = Url::toRoute(['save','region_id' => $id]);

        return $this->render('form',  compact('city','toUrl'));
    }

    public function actionUpdate($id){
        $city = City::find()
                    ->where(['id' => $id])
                    ->withText()->one();

        $toUrl = Url::toRoute(['save','id' => $id]);

        return $this->render('form',  compact('city','toUrl'));
    }

    public function actionSave($id = null, $region_id = null){
        $post = Yii::$app->request->post();

        if ($id){
            $city = City::findOne($id);
        } else {
            $city = new City();
            $city->regions_id = $region_id;
        }

        if (!$city->saveWithRelation($post)){

            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $city->getErrors(),
            ]);
        }

        return $this->sendJsonData([
                JsonData::SUCCESSMESSAGE => "Город \"{$city->_text->name}\" успешно сохранено",
                JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $city = City::findOne($id);
        $text = $city->_text;

        $city->delete();

        return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "Город \"{$text->name}\" успешно удален",
                    JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionSaveLang($id,$languages_id){
        $city = City::find()
                        ->where(['id' => $id])
                        ->withText($languages_id)
                        ->one();

        if ($this->isJson()){
            $text = $city->_mttext;
            $text->cities_id = $city->id;
            $text->languages_id = $languages_id;
            $text->load(Yii::$app->request->post());

            if ($text->save()){
                return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "\"{$text->name}\" успешно сохранено",
                    JsonData::REFRESHPAGE => '',
                ]);
            }

            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => \yii\widgets\ActiveForm::validate($text),
            ]);
        }

        return $this->render('savelang',[
            'city' => $city
        ]);
    }

    public function actionRemoveOrder(){
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $order = CityOrder::findOne($post['id']);
        if($order){
            $order->delete();
        }
        return true;
    }
}