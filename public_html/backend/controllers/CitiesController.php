<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\City;
use comon\models\CityText;
use common\helpers\JsonData;
use yii\helpers\Url;

/**
 * Site controller
 */
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
}