<?php
namespace backend\controllers;

use common\helpers\JsonData;
use common\models\AutopostingTasks;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class AutopostingTasksController extends BaseController
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

    public function actionIndex($task_id = null){
        $task = null;

        if ($task_id){
            $task = AutopostingTasks::findOne($task_id);
        } else {
            $tasks = AutopostingTasks::find()->orderBy('priority DESC, created_at ASC')->all();
        }

        return $this->render('index',  compact('task', 'tasks'));
    }

    public function actionCreate(){
        $task = new AutopostingTasks();

        $toUrl = Url::toRoute(['save']);

        return $this->render('form',  compact('task','toUrl'));
    }

    public function actionUpdate($id){
        $task = AutopostingTasks::find()
            ->where(['id' => $id])->one();

        $toUrl = Url::toRoute(['save','id' => $id]);

        return $this->render('form',  compact('task','toUrl'));
    }

    public function actionSave($id = null){
        $post = Yii::$app->request->post();

        if ($id){
            $task = AutopostingTasks::findOne($id);
        } else {
            $task = new AutopostingTasks();
        }
        $task->load($post);
        if (!$task->save()){
            $errors = [];
            foreach ($task->getErrors() as $key => $error){
                $errors['autopostingtasks-'.$key] = $error;
            }
            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $errors,
            ]);
        }
        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Успешно сохранено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){
        $sn = AutopostingTasks::findOne($id);
        $sn->delete();
        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Успешно удалено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionHandleTask(){

    }
}