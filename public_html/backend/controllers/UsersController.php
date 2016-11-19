<?php

namespace backend\controllers;

use backend\controllers\BaseController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\User;

class UsersController extends BaseController
{
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

    public function actionIndex()
    {
        $users = User::find()
                    ->with(['cities','cities.cityText'])->all();
//                    ->createCommand()->getRawSql();

        return $this->render('index',  compact('users'));
    }

    public function actionCreate()
    {
        $user = new User;

        return $this->render('create',  compact('user'));
    }

    public function actionUpdate($id)
    {
       $user = User::findOne($id);

        return $this->render('update',  compact('user'));
    }

    public function actionSave($id = null)
    {
        $user = ($id) ? User::findOne($id) : new User;

        $user->load(Yii::$app->request->post);
        $user->save();

        return $this->sendJsonData([
                JsonData::SUCCESSMESSAGE => "ПОльзотваель \"$user->fullName}\" успешно сохранен",
                JsonData::REFRESHPAGE => '',
        ]);
    }



}
