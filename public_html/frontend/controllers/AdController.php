<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use common\models\Category;
use common\models\Language;
use common\models\City;
use frontend\models\NewAdForm;

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
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
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

    public function actionAdd(){
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new NewAdForm();
        if (Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post(),'');
            $model->cities_id = Yii::$app->user->identity->cities_id;// пока ид города оставим захардкоженным
            if(!$model->validate()) {
                $errors = $model->getErrors();
                foreach($errors as $key => $item){
                    \Yii::$app->getSession()->setFlash($key.'_error', $item[0]);
                }
                return $this->redirect('podat-obiavlenie');
            }else{
                $model->newAd();
                \Yii::$app->getSession()->setFlash('message', __('Add successfully added.'));
                return $this->redirect('podat-obiavlenie');
            }
        } else {
            return $this->render('podat-obiavlenie');
        }
    }
}