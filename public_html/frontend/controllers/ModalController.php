<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\City;

/**
 * Контроллер для модальных форм
 *
 */

class ModalController extends Controller
{
   /**
    * Базовый шаблон модальной формы
    */
    protected $template = '/templates/modal';

    /**
    * Заголовок
    */
    public $title = '';

    /**
    * Содержимое (@view)
    */
    public $content = '';

    public function behaviors()
    {
        return [
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

   public function afterAction($action, $result) {

       $modal = $this;

       return $this->renderAjax($this->template, compact('modal'));
   }

    public function actionCities(){

        $cities = \common\models\City::find()
                        ->withText()
                        ->byLocation()
//                        ->asArray()
                        ->all();
       $cities = City::getComponentData($cities, \Yii::$app->request->referrer);

       $this->title = 'Город';
       $this->content = $this->renderPartial('cities',  compact('cities'));
    }
}
