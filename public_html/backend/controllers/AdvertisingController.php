<?php
namespace backend\controllers;

use common\helpers\JsonData;
use common\models\Advertising;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class AdvertisingController extends BaseController
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
        $advertising = Advertising::find()->all();

        return $this->render('index',compact('advertising'));
    }

    public function actionUpdate($id) {

        $advertising = Advertising::find()->where(['id' => $id])->one();


        $toUrl = Url::toRoute(['save', 'id' => $id]);

        return $this->renderAjax('form',compact('advertising', 'toUrl'));
    }

    public function actionCreate() {
        $advertising = new Advertising();

        $toUrl = Url::toRoute(['save']);

        return $this->renderAjax('form',  compact('advertising','toUrl'));
    }

    public function actionSave($id = null) {
        $post = Yii::$app->request->post();
        if($id){
            $advertising = Advertising::find()->where(['id' => $id])->one();
        }else{
            $advertising = new Advertising();
        }
        $advertising->load($post);
        if (!$advertising->save()){
            $errors = [];
            foreach($advertising->getErrors() as $key => $error){
                foreach($error as $k => $v){
                    $errors["advertising-".$key][$k] = $v;
                }
            }
            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $errors,
            ]);
        }
        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Успешно сохранено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete(){

        $advertising = Advertising::find()->one();
        $advertising->delete();

        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Успешно удалено",
            JsonData::REFRESHPAGE => '',
        ]);
    }
}