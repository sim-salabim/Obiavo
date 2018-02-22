<?php
namespace backend\controllers;


use common\models\libraries\AutopostingVk;
use common\models\SocialNetworks;

class AutopostingApiController extends BaseController
{

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
        $social_networks = SocialNetworks::find()->where(['autoposting' => 1])->all();
        if(count($social_networks)){
            foreach ($social_networks as $sn){
                $task = $sn->getActiveAutopostingTask();
                if($task){
                    switch ($sn->name){
                        case SocialNetworks::VK_COM :
                            (new AutopostingVk($task))->post();
                    }
                }
            }
        }
    }

}