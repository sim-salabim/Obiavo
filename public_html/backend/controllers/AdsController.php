<?php
namespace backend\controllers;

use common\models\Ads;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class AdsController extends BaseController
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

    public function actionSearchActive(){
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = $post['query'];
        $ads = Ads::find()
            ->where('expiry_date > '.time())
            ->andWhere("title LIKE '".$query."%'")
            ->all();
        $result = [];
        foreach($ads as $ad){
            $result[] = array('id' => $ad->id, 'text' => $ad->title);
        }
        return $result;
    }
}