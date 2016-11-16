<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Country;
use common\models\CountryText;
use common\helpers\JsonData;
use yii\helpers\Url;

/**
 * Site controller
 */
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
        $countries = Country::find()->with('countryText')->all();

        return $this->render('index',  compact('countries'));
    }

    public function actionAppend(){
        $country = new Country;
        $countryText = new CountryText;

        $toUrl = Url::toRoute('save');

        return $this->render('form',  compact('country','countryText','toUrl'));
    }

    public function actionEdit($id){
        $country = Country::find()
                        ->where(['id' => $id])
                        ->with('countryText')->one();

        $countryText = $country->countryText;

        $toUrl = Url::toRoute(['save','id' => $country->id]);

        return $this->render('form',  compact('country','countryText','toUrl'));
    }

    public function actionSave($id = null){
        $post = Yii::$app->request->post();

        if ($id){
            $country = Country::findOne($id);
        } else {
            $country = new Country();
        }

        $country->loadWithRelation(['countryText'],$post);
        $country->save();

        return $this->sendJsonData([
                JsonData::SUCCESSMESSAGE => "\"{$country->countryText->name}\" успешно сохранено",
                JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $country = Country::findOne($id);
        $text = $country->countryText;

        $country->delete();

        return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "Страна \"{$text->name}\" успешно удалена",
                    JsonData::REFRESHPAGE => '',
        ]);
    }
}