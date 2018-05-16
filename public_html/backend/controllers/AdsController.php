<?php
namespace backend\controllers;

use common\models\Ads;
use common\models\AutopostingTasks;
use common\models\libraries\AutopostingFb;
use common\models\libraries\AutopostingInstagram;
use common\models\libraries\AutopostingOk;
use common\models\libraries\AutopostingTwitter;
use common\models\libraries\AutopostingVk;
use common\models\SocialNetworks;
use common\models\SocialNetworksGroups;
use yii\base\ExitException;
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

    public function actionTest(){
        $post = \Yii::$app->request->get();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!isset($post['ad_id']) OR !isset($post['group_id'])){
            return ['message' => 'Отсутствует параметр'];
        }
        $ad = Ads::findOne($post['ad_id']);
        if(!$ad){
            return ['message' => 'Объявление '.$post['ad_id'].' отсутствует в системе'];
        }
        $group = SocialNetworksGroups::findOne($post['group_id']);
        $task = new AutopostingTasks();
        $task->social_networks_groups_id = $post['group_id'];
        $task->ads_id = $post['ad_id'];
        $task->status = AutopostingTasks::STATUS_PENDING;
        $task->priority = 1;
        $autoposting = null;
        switch($group->socialNetwork->name){
            case SocialNetworks::OK_RU :
                $autoposting = new AutopostingOk($task);
                break;
            case SocialNetworks::VK_COM :
                $autoposting = new AutopostingVk($task);
                break;
            case SocialNetworks::TWITTER :
                $autoposting = new AutopostingTwitter($task);
                break;
            case SocialNetworks::FB_COM :
                $autoposting = new AutopostingFb($task);
                break;
            case SocialNetworks::INSTAGRAM :
                $autoposting = new AutopostingInstagram($task);
                break;
        }
        try {
            $autoposting->post();
        }catch(\Exception $e){
            return ['message' => $e->getMessage()];
        }

        return ['message' => "Прошло успешно"];
    }
}