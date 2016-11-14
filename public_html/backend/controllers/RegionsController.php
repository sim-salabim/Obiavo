<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Region;
use common\models\RegionText;
use common\helpers\JsonData;
use yii\helpers\Url;

/**
 * Site controller
 */
class RegionsController extends BaseController
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

    public function actionIndex($country_id = null){

        if ($country_id){
            $country = \common\models\Country::findOne($country_id);
            $regions = $country->getRegions()->with('regionText')->all();
        } else {
            $regions = Region::find()->with('regionText')->all();
        }

        return $this->render('index',  compact('regions','country'));
    }
}