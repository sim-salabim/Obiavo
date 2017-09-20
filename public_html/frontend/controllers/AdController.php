<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use common\models\Category;
use common\models\Language;
use common\models\City;

class AdController extends BaseController
{
    public $params;

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


    public function actionNewAdd(){
        //TODO Finish form validation and implement file uploading with cropper
        $this->setPageTitle(__('Add ad'));
        $categories = Category::find()
            ->where(['parent_id' => NULL])
            ->withText(['laguages_id' => Language::getDefault()->id])
            ->all();
        $cities = City::find()->withText(['laguages_id' => Language::getDefault()->id])->all();
        return $this->render('new', [
            'user' => Yii::$app->user->identity,
            'categories' => $categories,
            'cities' => $cities,
        ]);
    }
}