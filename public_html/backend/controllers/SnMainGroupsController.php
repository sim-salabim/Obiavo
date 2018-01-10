<?php
namespace backend\controllers;

use common\models\SocialNetworksGroupsMain;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Country;
use common\models\CountryText;
use common\helpers\JsonData;
use yii\helpers\Url;

class SnMainGroupsController extends BaseController
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

    public function actionIndex(){
        $main_groups = SocialNetworksGroupsMain::find()->all();

        return $this->render('index',  compact('main_groups'));
    }

    public function actionCreate(){
        $main_group = new SocialNetworksGroupsMain();

        $toUrl = Url::toRoute('save');

        return $this->render('form',  compact('main_group','toUrl'));
    }

    public function actionUpdate($id){
        $main_group = SocialNetworksGroupsMain::find()
            ->where(['id' => $id])
            ->one();

        $toUrl = Url::toRoute(['save','id' => $main_group->id]);

        return $this->render('form',  compact('main_group','toUrl'));
    }

    public function actionSave($id = null){
        $post = Yii::$app->request->post();

        if ($id){
            $main_group = SocialNetworksGroupsMain::findOne($id);
        } else {
            $main_group = new SocialNetworksGroupsMain();
        }
        if (!$main_group->saveData($post['SocialNetworksGroupsMain'])){
            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $main_group->getErrors(),
            ]);
        }

        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "\"{$main_group->name}\" успешно сохранено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $main_group = SocialNetworksGroupsMain::findOne($id);
        $name = $main_group->name;
        $main_group->delete();

        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Группа \"{$name}\" успешно удалена",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionSearch(){
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = $post['query'];
        $sn_groups_main = SocialNetworksGroupsMain::find()
            ->where("name LIKE '".$query."%'")
            ->all();
        $result = [];
        foreach($sn_groups_main as $main_group){
            $result[$main_group->id] = array('id' => $main_group->id, 'text' => $main_group->name);
        }
        return $result;
    }
}