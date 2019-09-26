<?php
namespace backend\controllers;

use common\helpers\JsonData;
use common\models\AddApplication;
use common\models\AddApplicationText;
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
        $pages = AddApplication::find()->withText()->all();

        return $this->render('index',  compact('pages'));
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
            $page = AddApplication::findOne($id);
        } else {
            $page = new AddApplication();
        }

        if (!$page->saveWithRelation($post)){

            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $page->getErrors(),
            ]);
        }

        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "\"{$page->_text->url}\" успешно сохранено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $page = AddApplication::findOne($id);
        $text = $page->_text;

        $page->delete();

        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Страна \"{$text->url}\" успешно удалена",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionSaveLang($id,$languages_id){
        $page = AddApplication::find()
            ->where(['id' => $id])
            ->one();
        if(!$page){
            return $this->sendJsonData([
                JsonData::ERROR => "Страница с id ".$id." не найдена.",
            ]);
        }
        $mttext = AddApplicationText::find()->where(['add_application_id' => $id, 'languages_id'=>$languages_id])->one();
        if($mttext){
            $page->_text = $mttext;
        }else{
            $page->_text = new AddApplicationText();
            $page->_text->add_application_id = $page->id;
            $page->_text->languages_id = $languages_id;
        }
        if ($this->isJson()){
            $text = $mttext;
            $text->add_application_id = $page->id;
            $text->languages_id = $languages_id;
            $text->load(Yii::$app->request->post());

            if ($text){
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