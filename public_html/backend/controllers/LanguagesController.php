<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Language;
use common\models\LanguageText;
use common\helpers\JsonData;
use yii\helpers\Url;

/**
 * Site controller
 */
class LanguagesController extends BaseController
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
        $languages = Language::find()->all();

        return $this->render('index',  compact('languages'));
    }

    public function actionAppend(){
        $language = new Language;
        $toUrl = Url::toRoute(['save']);

        return $this->render('form',  compact('language','toUrl'));
    }

    public function actionEdit($id){
        $language = Language::findOne($id);
        $toUrl = Url::toRoute(['save','id' => $language->id]);

        return $this->render('form',  compact('language','toUrl'));
    }

    public function actionSave($id = null){
        $post = Yii::$app->request->post();

        if ($id){
            $lang = Language::findOne($id);
        } else {
            $lang = new Language();
        }

        $lang->loadWithRelation(['text'],$post);
        $lang->save();

        return $this->sendJsonData([
                JsonData::SUCCESSMESSAGE => "\"{$lang->text->name}\" успешно сохранено",
                JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $lang = Language::findOne($id);
        $text = $lang->getText()->one();

        $lang->delete();

        return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "Языковой пункт \"{$text->name}\" успешно удален",
                    JsonData::REFRESHPAGE => '',
        ]);
    }
}