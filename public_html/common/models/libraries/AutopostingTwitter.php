<?php
namespace common\models\libraries;

use Abraham\TwitterOAuth\TwitterOAuth as TwitterAuth;
use common\models\AutopostingTasks;
use common\models\Mailer;
use yii\base\Exception;

class AutopostingTwitter {

    const FILES_LIMIT = 4;
    const FILE_SIZE_LIMIT = 5000000;

    private $task;
    private $group;

    function __construct(AutopostingTasks $task){
        $this->task = $task;
        $this->group = $this->task->socialNetworksGroup;
    }

    function post(){
        $connection = new TwitterAuth($this->group->consumer_key, $this->group->consumer_secret, $this->group->group_id, $this->group->token);
        $task_files = $this->task->ad->files;
        $media_ids = '';
        $media_amount = 0;
        // зальем файлы если такие есть и подхотят по требованиям
        \Yii::warning("АT P Найдено файлов ".count($task_files)." для задачи ".$this->task->id.", обьявления ".$this->task->ad->id, "DEBUG");
        foreach($task_files as $file){
            if($media_amount < self::FILES_LIMIT AND filesize (\Yii::$app->params['uploadPath']."/".$file->hash) < self::FILE_SIZE_LIMIT){
                try {
                    $media = $connection->upload("media/upload", array('media' => \Yii::$app->params['uploadPath'] . "/" . $file->hash));
                }catch(Exception $e){
                    TelegrammLoging::send('Ошибка размещения медиафайлов в Twitter  ID сообщества: '.$this->task->socialNetworksGroup->id);
                    \Yii::warning("АT P Ошибка размещения медиафайлов в Twitter  ID сообщества: ".$this->task->socialNetworksGroup->id, "DEBUG");
                    unset($media);
                }
                if(isset($media) AND !isset($media->errors)) {
                    if($media_ids == ''){
                        $media_ids .= $media->media_id_string;
                    }else{
                        $media_ids .= ','.$media->media_id;
                    }
                    $media_amount++;
                }
            }
        }

        $statuses = $connection->post("statuses/update", array("status" => $this->task->ad->title, 'media_ids' => $media_ids));
        if($connection->getLastHttpCode() != 200){
            TelegrammLoging::send('Ошибка публикации в Twitter  ID сообщества: '.$this->task->socialNetworksGroup->id.' '.$statuses->errors[0]->message);
            Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API Twitter", 'api-error', ['message' =>$statuses->errors[0]->message]);
            $this->task->status = AutopostingTasks::STATUS_FAILED;
            $this->task->save();
            \Yii::warning('АT P Ошибка публикации в Twitter  ID сообщества: '.$this->task->socialNetworksGroup->id.' '.$statuses->errors[0]->message, "DEBUG");
        }else{
            $this->task->status = AutopostingTasks::STATUS_POSTED;
            $this->task->save();
            \Yii::warning('АT P Задача '.$this->task->id.' выполнена успешно', "DEBUG");
        }
    }
}