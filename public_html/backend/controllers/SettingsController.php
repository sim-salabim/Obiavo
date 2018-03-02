<?php
namespace backend\controllers;

use common\helpers\JsonData;
use common\models\Settings;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class SettingsController extends BaseController
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
        $setting = Settings::find()->all();

        return $this->render('index',compact('setting'));
    }

    public function actionUpdate() {

        $setting = Settings::find()->one();


        $toUrl = Url::toRoute(['save']);

        return $this->renderAjax('form',compact('setting', 'toUrl'));
    }

    public function actionCreate() {
        $setting = new Settings();

        $toUrl = Url::toRoute(['save']);

        return $this->renderAjax('form',  compact('setting','toUrl'));
    }

    public function actionSave() {
        $post = Yii::$app->request->post();
        $setting = Settings::find()->one();
        if (!$setting){
            $setting = new Settings();
        }
        $setting->load($post);
        if (!$setting->save()){
            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $setting->getErrors(),
            ]);
        }
        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Успешно сохранено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete(){

        $setting = Settings::find()->one();
        $setting->delete();

        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Успешно удалено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

}