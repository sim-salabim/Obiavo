<?php
namespace backend\controllers;

use common\helpers\JsonData;
use common\models\AddApplication;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class ApplicationSeoController extends BaseController
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
        $countries = AddApplication::find()->withText()->all();

        return $this->render('index',  compact('countries'));
    }

    public function actionCreate(){
        $page = new AddApplication();

        $toUrl = Url::toRoute('save');
        return $this->render('form',  compact('page','toUrl'));
    }

    public function actionUpdate($id){
        $page = AddApplication::find()
            ->where(['id' => $id])
            ->withText()
            ->one();

        $toUrl = Url::toRoute(['save','id' => $page->id]);

        return $this->render('form',  compact('page','toUrl'));
    }

    public function actionSave($id = null){
        $post = Yii::$app->request->post();

        if ($id){
            $country = AddApplication::findOne($id);
        } else {
            $country = new AddApplication();
        }

        if (!$country->saveWithRelation($post)){

            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $country->getErrors(),
            ]);
        }

        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "\"{$country->_text->url}\" успешно сохранено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $country = AddApplication::findOne($id);
        $text = $country->_text;

        $country->delete();

        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Страна \"{$text->url}\" успешно удалена",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionSaveLang($id,$languages_id){
        $page = AddApplication::find()
            ->where(['id' => $id])
            ->withText($languages_id)
            ->one();

        if ($this->isJson()){
            $text = $page->_mttext;
            $text->add_application_id = $page->id;
            $text->languages_id = $languages_id;
            $text->load(Yii::$app->request->post());

            if ($text->save()){
                return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "\"{$text->url}\" успешно сохранено",
                    JsonData::REFRESHPAGE => '',
                ]);
            }

            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => \yii\widgets\ActiveForm::validate($text),
            ]);
        }

        return $this->render('savelang',[
            'page' => $page
        ]);
    }

}