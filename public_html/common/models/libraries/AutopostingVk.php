<?php
namespace common\models\libraries;

use common\models\AutopostingTasks;
use common\models\Files;
use common\models\Mailer;

class AutopostingVk {

    const API_VERSION = '5.73';
    const ENDPOINT_WALL_POST = 'wall.post';
    const ENDPOINT_COUNT_ALBUMS = 'photos.getAlbumsCount';
    const ENDPOINT_GET_ALBUMS = 'photos.getAlbums';
    const ENDPOINT_CREATE_ALBUM = 'photos.createAlbum';
    const ENDPOINT_PHOTOS_SAVE = 'photos.save';
    const ENDPOINT_PHOTOS_UPLOAD_SERVER = 'photos.getUploadServer';

    private $access_token;
    private $api_url;
    private $task;

    function __construct(AutopostingTasks $task){
        $this->task = $task;
        $this->access_token = \Yii::$app->params['VK_access_token'];
        $this->api_url = 'https://api.vk.com/method/{endpoint:key}?access_token='.$this->access_token.'&v='.self::API_VERSION;
    }

    function post(){
        $attachements = '';
        if(count($this->task->ad->files)){
            file_get_contents('https://api.telegram.org/bot517180739:AAG_ZzuRtwArLMOeX7xEXYP9NXoEJIasPnk/sendMessage?text="есть файлы у обьявления"&chat_id=88740047');
            $album = $this->createAlbumIfNotExists();
            $photos_uploaded = [];
            if($album) $photos_uploaded = $this->uploadPhotos($album->id);
            if(!empty($photos_uploaded)){
                $attachements .= 'attachments=';
                foreach ($photos_uploaded as $i => $photo){
                    $attachements .= 'photo'.$photo->owner_id.'_'.$photo->id;
                    $next = $i + 1;
                    if(isset($photos_uploaded[$next])) $attachements .= ',';
                }
            }
        }
        $api_request_str = str_replace('{endpoint:key}', self::ENDPOINT_WALL_POST, $this->api_url);
        $api_request_str .= '&from_group=1&owner_id=-'.$this->task->socialNetworksGroup->group_id.'&message='.$this->task->ad->text.'&'.$attachements;
        $result = json_decode(file_get_contents($api_request_str));
        file_get_contents('https://api.telegram.org/bot517180739:AAG_ZzuRtwArLMOeX7xEXYP9NXoEJIasPnk/sendMessage?text="try to post"&chat_id=88740047');
        if(isset($result->error)){
            file_get_contents('https://api.telegram.org/bot517180739:AAG_ZzuRtwArLMOeX7xEXYP9NXoEJIasPnk/sendMessage?text="error '.$result->error->error_msg.'"&chat_id=88740047');
            Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API VK.COM", 'api-error', ['error' => $result->error->error_msg, 'request' => $api_request_str, 'message' => 'Ошибка получения сервера для загрузки фото']);
            $this->task->status = AutopostingTasks::STATUS_FAILED;
            $this->task->save();
        }else if(isset($result->response)){
            $task = $this->task;
            $task->status = AutopostingTasks::STATUS_POSTED;
            $this->task->posted_at = humanDate(time());
            $task->save();
        }
    }

    private function uploadPhotos($album_id){
        $api_request_get_server = str_replace('{endpoint:key}', self::ENDPOINT_PHOTOS_UPLOAD_SERVER, $this->api_url);
        $api_request_get_server .= '&album_id='.$album_id.'&group_id='.$this->task->socialNetworksGroup->group_id;
        $api_request_get_server_response = json_decode(file_get_contents($api_request_get_server));
        if(isset($api_request_get_server_response->error)){
            Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API VK.COM", 'vapi-error', ['error' => $api_request_get_server_response->error->error_msg, 'request' => $api_request_get_server, 'message' => 'Ошибка получения сервера для загрузки фото']);
            return;
        }
        $idx = 1;
        $file_list = [];
        foreach ($this->task->ad->files as $file){
            if(($file->ext->ext == Files::JPG_EXT OR $file->ext->ext == Files::JPEG_EXT OR $file->ext->ext == Files::PNG_EXT OR $file->ext->ext == Files::GIF_EXT) AND getimagesize($file->getFilePathWithoutHash())){// файлы других форматов vk api не принимает
                $image_size = getimagesize($file->getFilePathWithoutHash());
                if(filesize($file->getFilePathWithoutHash()) < 50000000
                    AND ($image_size[0]+$image_size[1] < 14000)
                    AND ($image_size[1]/$image_size[0] > 0.05 OR $image_size[0]/$image_size[1] > 0.05)
                    AND $idx < 6){//можно загрузить не более 5-и файлов, файлы больше 50мб, с суммой сторон больше 14000px и соотношением сторон более 1/20 vk api не принимает
                    $file_list['file'.$idx] = new \CURLFile($file->getFilePathWithoutHash(), $file->ext->mime, $file->hash.".".$file->ext->ext);
                    ++$idx;
                }
            }
        }
        if(!empty($file_list)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_request_get_server_response->response->upload_url);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:multipart/form-data'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $file_list);
            $resp = curl_exec($ch);
            $info = curl_getinfo($ch);
            $errno = curl_errno($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            curl_close($ch);
            $resp_body = json_decode(substr($resp, $header_size));
            if(!$errno AND $info['http_code'] == 200)
            {
                $api_request_save_photos = str_replace('{endpoint:key}', self::ENDPOINT_PHOTOS_SAVE, $this->api_url);
                $api_request_save_photos .= '&group_id='.$this->task->socialNetworksGroup->group_id.'&album_id='.$resp_body->aid.'&server='.$resp_body->server.'&hash='.$resp_body->hash.'&photos_list='.$resp_body->photos_list;
                $api_save_photos_response = json_decode(file_get_contents($api_request_save_photos));
                if(isset($api_save_photos_response->error)){
                    Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API VK.COM", 'api-error', ['error' => $api_save_photos_response->error->error_msg, 'request' => $api_request_get_server_response->response->upload_url, 'message' => 'Ошибка получения сервера для загрузки фото']);
                }else if(isset($api_save_photos_response->response)){
                    return $api_save_photos_response->response;
                }
            }else{
                Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API VK.COM", 'api-error', ['error' => "Ошибка CURL запроса во время загрузки фотографий", 'request' => $api_request_get_server_response->response->upload_url, 'message' => 'Ошибка получения сервера для загрузки фото']);
            }
        }
        return [];
    }

    /**
     *  Проверяет есть ли у сообщества альбомы в кототрые можно грузить фото, если нет, то создает и возвращает
     *  id альбома
     *  при ошибках возвращаемых с VK API возвращает null, и шлет админу на е-меил отчет
     *
     * @return null|int
     */
    private function createAlbumIfNotExists(){
        $api_request_get_albums = str_replace('{endpoint:key}', self::ENDPOINT_GET_ALBUMS, $this->api_url);
        $api_request_get_albums .= '&owner_id=-'.$this->task->socialNetworksGroup->group_id;
        $get_albums_response = json_decode(file_get_contents($api_request_get_albums));
        if(!isset($get_albums_response->response->count)){
            if(isset($get_albums_response->error)){
                Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API VK.COM", 'api-error', ['error' => $get_albums_response->error, 'request' => $api_request_get_albums, 'message' => 'Ошибка извлечения альбомов']);
            }
            return null;
        }
        $album_id = null;
        if($get_albums_response->response->count == 0){
            $api_request_create_album = str_replace('{endpoint:key}', self::ENDPOINT_CREATE_ALBUM, $this->api_url);
            $api_request_create_album .= '&group_id='.$this->task->socialNetworksGroup->group_id.'&title="Объявления"&upload_by_admins_only=1&comments_disabled=1&';
            $album_created_response = json_decode(file_get_contents($api_request_create_album.'&group_id='.$this->task->socialNetworksGroup->group_id.'&title="Объявления"&upload_by_admins_only=1&comments_disabled=1&'));
            if(!isset($album_created_response->error)){
                $album_id = $album_created_response->response->id;
            }else{
                Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API VK.COM", 'api-error', ['error' => $album_created_response->error, 'request' => $api_request_create_album, 'message' => 'Ошибка создания альбома']);
                return null;
            }
        }else{
            $album_id = $get_albums_response->response->items[0];
        }
        return $album_id;
    }
}