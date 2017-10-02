<?php
namespace frontend\controllers;

use frontend\models\SettingsForm;
use Yii;

class UsersController extends BaseController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function actionIm(){

        return $this->render('index');
    }

    public function actionSettings(){
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new SettingsForm();
        if (Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post(),'');
            if(!$model->validate()) {
                $errors = $model->getErrors();
                foreach($errors as $key => $item){
                    \Yii::$app->getSession()->setFlash($key.'_error', $item[0]);
                }
                return $this->redirect('/nastroiki');
            }else{
                $model->changeSettings();
                \Yii::$app->getSession()->setFlash('message', __('Successfully saved'));
                return $this->redirect('/nastroiki');
            }
        } else {
            return $this->render('settings');
        }
    }

    public function actionMyAds(){
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->setPageTitle(__('My ads'));
        return $this->render('my-ads', ['ads' => Yii::$app->user->getIdentity()->ads]);
    }
}
