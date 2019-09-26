<?php
namespace backend\controllers;

use common\helpers\JsonData;
use common\models\Country;
use common\models\SitemapTasks;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class SitemapController extends BaseController
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
        $countries = Country::find()->withText()->all();

        return $this->render('index',  compact('countries'));
    }

    public function actionStart(){
        $tasks = SitemapTasks::find()->where(['status'=> SitemapTasks::PENDING_STATUS, "status"=>SitemapTasks::PROCESSING_STATUS])->all();

        return $this->render('start',  compact('tasks'));
    }

    public function actionList(){
        $tasks = SitemapTasks::find()->orderBy('created_at', "DESC")->all();
        return $this->render('task-list', compact('tasks'));
    }

    public function actionRun($id){
        $country = Country::findOne($id);
        $sitemap_task = new SitemapTasks();
        $sitemap_task->countries_id = $country->id;
        $sitemap_task->status = SitemapTasks::PENDING_STATUS;
        $sitemap_task->save();
        $toUrl = Url::toRoute('list');
        $this->redirect($toUrl);
//        return $this->render('task-list',  compact('country','toUrl'));
    }

}