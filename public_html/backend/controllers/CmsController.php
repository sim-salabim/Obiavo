<?php
namespace backend\controllers;

use common\models\Cms;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\helpers\JsonData;
use yii\helpers\Url;

class CmsController extends BaseController
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $cms_pages = Cms::find()->all();

        return $this->render('index',compact('cms_pages'));
    }

    public function actionUpdate($id) {

        $cms = Cms::findOne($id);
        $text = $cms->cmsText;

        $cmsText = $text ? $text : new \common\models\CmsText;

        $toUrl = Url::toRoute(['save','id' => $cms->id]);

        return $this->renderAjax('form',compact('cms','cmsText', 'toUrl'));
    }

    public function actionCreate() {
        $cms= new Cms();
        $cmsText = new \common\models\CmsText();

        $toUrl = Url::toRoute(['save']);

        return $this->renderAjax('form',  compact('cms','cmsText','toUrl'));
    }

    public function actionSave($id = null) {
        $post = Yii::$app->request->post();

        if ($id){
            $cms = Cms::findOne($id);
        } else {
            $cms = new Cms;
        }
        if (!$cms->saveWithRelation($post)){
            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $cms->getErrors(),
            ]);
        }
        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "\"{$cms->techname}\" успешно сохранено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $cms = Cms::findOne($id);
        $cms->delete();

        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "\"{$cms->techname}\" успешно удалено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionSaveLang($id,$languages_id){
        $cms = Cms::find()
            ->where(['id' => $id])
            ->withText($languages_id)
            ->one();

        if ($this->isJson()){
            $text = $cms->_mttext;
            $text->cms_id = $cms->id;
            $text->languages_id = $languages_id;
            $text->load(Yii::$app->request->post());

            if ($text->save()){
                return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "\"{$cms->techname}\" успешно сохранено",
                    JsonData::REFRESHPAGE => '',
                ]);
            }

            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => \yii\widgets\ActiveForm::validate($text),
            ]);
        }

        return $this->render('savelang',[
            'cms' => $cms,
        ]);
    }
}