<?php
namespace backend\controllers;

use common\models\City;
use common\models\SitemapIndex;
use common\models\SitemapTasks;
use Yii;
use yii\db\Query;
use yii\web\Controller;

class SitemapCronController extends Controller
{

    private $task;
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

    public function actionIndex(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       // $this->checkCredentials();
        $this->task = SitemapTasks::find()->where(['status'=>SitemapTasks::PROCESSING_STATUS])->one();
        if(!$this->task){
            $this->task = SitemapTasks::find()->where(['status'=>SitemapTasks::PENDING_STATUS])->one();
        }
        if($this->task){
            $this->handleTask();
        }
        return ['success'];
    }

    private function handleTask(){
        for($i = 1;$i <= 5; $i++){
            $sm = new SitemapIndex();
            if($i < 3){
                $sm->link = "http://".$this->task->country->domain."/sitemap".$i.".xml";
            }else{
                $sm->link = "http://".$this->task->country->domain."/sitemap".$i.".pl.xml";
            }
            $sm->tasks_id = $this->task->id;
            $sm->countries_id = $this->task->countries_id;
            $sm->save();
        }
        $cities = City::find()->where(['in', 'regions_id',
                    (new Query())->select('id')->from('regions')->where(['countries_id' => $this->task->countries_id])
                ])->andWhere(['sitemap'=>1])->all();

        for($idx = 0; $idx <= count($cities) - 1; $idx++){
            $sm = new SitemapIndex();
            $sm->link = "http://".$this->task->country->domain."/sitemap".$i.".city_".$cities[$idx]->id."_0.xml";
            $sm->tasks_id = $this->task->id;
            $sm->countries_id = $this->task->countries_id;
            $sm->save();
            $i++;
            $sm = new SitemapIndex();
            $sm->link = "http://".$this->task->country->domain."/sitemap".$i.".city_".$cities[$idx]->id."_1.xml";
            $sm->tasks_id = $this->task->id;
            $sm->countries_id = $this->task->countries_id;
            $sm->save();
            $i++;
            $sm = new SitemapIndex();
            $sm->link = "http://".$this->task->country->domain."/sitemap".$i.".city_".$cities[$idx]->id.".pl_0.xml";
            $sm->tasks_id = $this->task->id;
            $sm->countries_id = $this->task->countries_id;
            $sm->save();
            $i++;
            $sm = new SitemapIndex();
            $sm->link = "http://".$this->task->country->domain."/sitemap".$i.".city_".$cities[$idx]->id.".pl_1.xml";
            $sm->tasks_id = $this->task->id;
            $sm->countries_id = $this->task->countries_id;
            $sm->save();
            $i++;
            $sm = new SitemapIndex();
            $sm->link = "http://".$this->task->country->domain."/sitemap".$i.".city_".$cities[$idx]->id.".pl_2.xml";
            $sm->tasks_id = $this->task->id;
            $sm->countries_id = $this->task->countries_id;
            $sm->save();
        }
        $this->task->status = SitemapTasks::FINISHED_STATUS;
        $this->task->save();
    }


}