<?php
namespace backend\controllers;

use common\helpers\JsonData;
use common\models\Country;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class CountriesController extends BaseController
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

    public function actionIndex(){
        $countries = Country::find()->withText()->all();

        return $this->render('index',  compact('countries'));
    }

    public function actionCreate(){
        $country = new Country;

        $toUrl = Url::toRoute('save');

        return $this->render('form',  compact('country','toUrl'));
    }

    public function actionUpdate($id){
        $country = Country::find()
                        ->where(['id' => $id])
                        ->withText()
                        ->one();

        $toUrl = Url::toRoute(['save','id' => $country->id]);

        return $this->render('form',  compact('country','toUrl'));
    }

    public function actionSave($id = null){
        $post = Yii::$app->request->post();

        if ($id){
            $country = Country::findOne($id);
        } else {
            $country = new Country();
        }

        if (!$country->saveWithRelation($post)){

            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $country->getErrors(),
            ]);
        }

        return $this->sendJsonData([
                JsonData::SUCCESSMESSAGE => "\"{$country->_text->name}\" успешно сохранено",
                JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $country = Country::findOne($id);
        $text = $country->_text;

        $country->delete();

        return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "Страна \"{$text->name}\" успешно удалена",
                    JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionSaveLang($id,$languages_id){
        $country = Country::find()
                        ->where(['id' => $id])
                        ->withText($languages_id)
                        ->one();

        if ($this->isJson()){
            $text = $country->_mttext;
            $text->countries_id = $country->id;
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
            'country' => $country
        ]);
    }
}