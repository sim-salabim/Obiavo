<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\City;
use common\models\CityText;
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

    public function actionIndex($region_id = null){

        if ($region_id){
            $region = \common\models\Region::findOne($region_id);
            $cities = $region->getCities()->with('cityText')->all();
        } else {
            $cities = City::find()->with('cityText')->all();
        }

        return $this->render('index',  compact('region','cities'));
    }
}