<?php
namespace common\models\libraries;

use Abraham\TwitterOAuth\TwitterOAuth as TwitterAuth;
use common\models\AutopostingTasks;
use common\models\Mailer;

class AutopostingTwitter {

    private $task;
    private $group;

    function __construct(AutopostingTasks $task){
        $this->task = $task;
        $this->group = $this->task->socialNetworksGroup;
    }

    function post(){

        $connection = new TwitterAuth($this->group->consumer_key, $this->group->consumer_secret, $this->group->group_id, $this->group->token);
        $statuses = $connection->post("statuses/update", array("status" => $this->task->ad->title));
        if($connection->getLastHttpCode() != 200){
            TelegrammLoging::send('Ошибка публикации в Twitter  ID сообщества'.$this->task->socialNetworksGroup->id.' '.$statuses->errors[0]->message);
            Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API Twitter", 'api-error', ['message' =>$statuses->errors[0]->message]);
            $this->task->status = AutopostingTasks::STATUS_FAILED;
            $this->task->save();
        }else{
            $this->task->status = AutopostingTasks::STATUS_POSTED;
            $this->task->save();
        }
    }
}