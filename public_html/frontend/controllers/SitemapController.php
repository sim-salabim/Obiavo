<?php
namespace frontend\controllers;

use common\models\SitemapIndex;
use frontend\components\Location;
use Yii;


class SitemapController extends BaseController
{
    public $layout=false;
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        $links = SitemapIndex::find()->where(['countries_id' => Yii::$app->location->country->id])->all();
        return $this->renderPartial('index', compact('links'));
    }

}
