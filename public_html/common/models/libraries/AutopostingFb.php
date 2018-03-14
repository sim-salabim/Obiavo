<?php
namespace common\models\libraries;

use common\models\AutopostingTasks;
use common\models\Mailer;
use common\models\Settings;

class AutopostingFb {

    const API_VERSION = '2.12';

    private $task;
    private $group_id;
    private $token;

    function __construct(AutopostingTasks $task){
        $this->task = $task;
        $this->group_id = $this->task->socialNetworksGroup->group_id;
        $token = null;
        if($task->socialNetworksGroup->token){
            $this->token = $task->socialNetworksGroup->token;
        }else{
            $settings = Settings::find()->one();
            if($settings AND $settings->fb_token){
                $this->token = $settings->fb_token;
            }
        }
    }

    function post(){
        $postfields = [
            'message' => $this->task->ad->text,
            'access_token' => $this->token,
            'link' => $this->task->ad->city->region->country->domain.'/'.$this->task->ad->url()
        ];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/{$this->group_id}/feed");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Content-Type:multipart/form-data"));
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $postfields);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($response != 200){
            TelegrammLoging::send('Ошибка публикации на странице Facebook '.$this->task->socialNetworksGroup->group_id.' https://graph.facebook.com/'.$this->group_id.'/feed access_token => '.$this->token);
            Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API Facebook", 'api-error', [ 'request' => $this->task->socialNetworksGroup->group_id.' https://graph.facebook.com/'.$this->group_id.'/feed access_token => '.$this->token.', message =>'.$postfields['message'].', link => '.$postfields['link'], 'message' => 'Ошибка публикации на странице Facebook']);
            $this->task->status = AutopostingTasks::STATUS_FAILED;
            $this->task->save();
        }else{
            $this->task->status = AutopostingTasks::STATUS_POSTED;
            $this->task->save();
        }
    }
}