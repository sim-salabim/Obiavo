<?php
namespace common\models\libraries;

use common\models\AutopostingTasks;
use common\models\Files;
use common\models\Mailer;

class AutopostingInstagram {

    const IMAGE_WIDTH_MIN = 320;
    const IMAGE_WIDTH_MAX = 1080;

    private $task;
    private $group;

    function __construct(AutopostingTasks $task){
        $this->task = $task;
        $this->group = $this->task->socialNetworksGroup;
    }

    function post(){
        if(count($this->task->ad->files)) {
            set_time_limit(0);
            date_default_timezone_set('UTC');
            $username = $this->task->socialNetworksGroup->group_id;
            $password = $this->task->socialNetworksGroup->token;
            $debug = true;
            $truncatedDebug = false;
            $photoFilename = \Yii::$app->params['uploadPath'] . "/" . $this->task->ad->files[0]->hash;
            $captionText = '#obiavoRu ' . $this->task->ad->text;
            $ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
            try {
                $ig->login($username, $password);
            } catch (\Exception $e) {
//                $this->task->status = AutopostingTasks::STATUS_FAILED;
//                $this->task->save();
                echo 'Ошибка публикации в Instagramm  ID сообщества: '.$this->task->socialNetworksGroup->id.', ID здачи: '.$this->task->id.' '.$e->getMessage();
//                TelegrammLoging::send('Ошибка публикации в Instagramm  ID сообщества: '.$this->task->socialNetworksGroup->id.', ID здачи: '.$this->task->id.' '.$e->getMessage());
//                Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API Instagram", 'api-error', ['message' =>$e->getMessage()]);
                exit(0);
            }
            list($width, $height) = getimagesize($photoFilename);
            if ($width > self::IMAGE_WIDTH_MAX OR $width < self::IMAGE_WIDTH_MIN) {
                switch ($this->task->ad->files[0]->ext->ext) {
                    case Files::GIF_EXT :
                        $thumb = imagecreatefromgif(\Yii::$app->params['uploadPath'] . "/" . $this->task->ad->files[0]->hash);
                        break;
                    case Files::JPEG_EXT :
                        $thumb = imagecreatefromjpeg(\Yii::$app->params['uploadPath'] . "/" . $this->task->ad->files[0]->hash);
                        break;
                    case Files::JPG_EXT :
                        $thumb = imagecreatefromjpeg(\Yii::$app->params['uploadPath'] . "/" . $this->task->ad->files[0]->hash);
                        break;
                    case Files::PNG_EXT:
                        $thumb = imagecreatefrompng(\Yii::$app->params['uploadPath'] . "/" . $this->task->ad->files[0]->hash);
                        break;
                }
            }
            if($width > self::IMAGE_WIDTH_MAX){
                $difference = $width - self::IMAGE_WIDTH_MAX;
                $x = $difference/2;
                $im2 = imagecrop($thumb, ['x' => $x, 'y' => 0, 'width' => self::IMAGE_WIDTH_MAX, 'height' => $height]);
                imagejpeg($im2, \Yii::$app->params['uploadPath'] . "/" . $this->task->ad->files[0]->hash."_copy");
                $photoFilename = \Yii::$app->params['uploadPath'] . "/" . $this->task->ad->files[0]->hash."_copy";
            }
            if($width < self::IMAGE_WIDTH_MIN){
                $difference = self::IMAGE_WIDTH_MIN - $width;
                $x = $difference/2;
                $im2 = imagecrop($thumb, ['x' => -$x, 'y' => 0, 'width' => self::IMAGE_WIDTH_MIN, 'height' => $height]);
                imagejpeg($im2, \Yii::$app->params['uploadPath'] . "/" . $this->task->ad->files[0]->hash."_copy");
                $photoFilename = \Yii::$app->params['uploadPath'] . "/" . $this->task->ad->files[0]->hash."_copy";
            }
            try {
                $ig->timeline->uploadPhoto($photoFilename, ['caption' => $captionText]);
            } catch (\Exception $e) {
                echo 'Something went wrong: ' . $e->getMessage() . "\n";
                $this->task->status = AutopostingTasks::STATUS_FAILED;
                $this->task->save();
                TelegrammLoging::send('Ошибка публикации в Instagramm  ID сообщества: '.$this->task->socialNetworksGroup->id.' '.$e->getMessage());
                Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API Instagram", 'api-error', ['message' =>$e->getMessage()]);
                exit(0);
            }
//            $this->task->status = AutopostingTasks::STATUS_POSTED;
//            $this->task->save();
        }else{
//            $this->task->status = AutopostingTasks::STATUS_FAILED;
//            $this->task->save();
        }
    }
}