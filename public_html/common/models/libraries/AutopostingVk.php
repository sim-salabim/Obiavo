<?php
namespace common\models\libraries;

use common\models\AutopostingTasks;
use common\models\Files;
use common\models\Mailer;
use common\models\Settings;

class AutopostingVk {

    const API_VERSION = '5.73';
    const ENDPOINT_WALL_POST = 'wall.post';
    const ENDPOINT_COUNT_ALBUMS = 'photos.getAlbumsCount';
    const ENDPOINT_GET_ALBUMS = 'photos.getAlbums';
    const ENDPOINT_CREATE_ALBUM = 'photos.createAlbum';
    const ENDPOINT_PHOTOS_SAVE = 'photos.save';
    const ENDPOINT_PHOTOS_UPLOAD_SERVER = 'photos.getUploadServer';
    const VK_PHOTOS_LIMIT_PER_ALBUM = 9950;

    private $access_token;
    private $api_url;
    private $task;

    function __construct(AutopostingTasks $task){
        $this->task = $task;
        $vk_token = null;
        if($task->socialNetworksGroup->token){
            $vk_token = $task->socialNetworksGroup->token;
        }else{
            $settings = Settings::find()->one();
            if($settings AND $settings->vk_token){
                $vk_token = $settings->vk_token;
            }
        }
        $this->access_token = $vk_token;
        $this->api_url = 'https://api.vk.com/method/{endpoint:key}?access_token='.$this->access_token.'&v='.self::API_VERSION;
    }

    function post(){
        if($this->access_token) {
            $attachements = '';
            \Yii::warning("АVK P Найдено файлов ".count($this->task->ad->files)." для задачи ".$this->task->id.", обьявления ".$this->task->ad->id, "DEBUG");
            if (count($this->task->ad->files)) {
                $album = $this->createAlbumIfNotExists();
                $photos_uploaded = [];
                if ($album) $photos_uploaded = $this->uploadPhotos($album->id);
                if (!empty($photos_uploaded)) {
                    $attachements .= 'attachments=';
                    foreach ($photos_uploaded as $i => $photo) {
                        $attachements .= 'photo' . $photo->owner_id . '_' . $photo->id;
                        $next = $i + 1;
                        if (isset($photos_uploaded[$next])) $attachements .= ',';
                    }
                }
            }
            $message = file_get_contents(\Yii::getAlias('@common').'/views/vk-post.php');
            $post_text = $this->task->ad->text;
            if(mb_strlen($post_text) > 500){
                $post_text  = mb_substr($post_text, 0, 497)."...";
            }
            $message = str_replace(['{key:url}', '{key:title}', '{key:price}', '{key:text}', '{key:price-text}'], [\Yii::$app->params['rootUrl'].$this->task->ad->url(), $this->task->ad->title, $this->task->ad->price, $post_text, "Цена"], $message);
            $api_request_str = str_replace('{endpoint:key}', self::ENDPOINT_WALL_POST, $this->api_url);
            $api_request_str .= '&from_group=1&owner_id=-' . $this->task->socialNetworksGroup->group_id . '&message=' . urlencode($message) . '&' . $attachements;
            $result = json_decode(file_get_contents($api_request_str));
            if (isset($result->error)) {
                $this->task->status = AutopostingTasks::STATUS_FAILED;
                $this->task->save();
                TelegrammLoging::send('Ошибка публикации поста на стене для группы ' . $this->task->socialNetworksGroup->group_id . ' ' . $result->error->error_msg . ' ' . $api_request_str);
                Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API VK.COM", 'api-error', ['error' => $result->error->error_msg, 'request' => $api_request_str, 'message' => 'Ошибка публикации поста на стене', 'details' => 'Произошла ошибка публикации в на стене <a href="' . $this->task->socialNetworksGroup->url . '">сообщества</a>. <a href="https://vk.com/dev/wall.post">Документация по вызванному методу</a>']);
            } else if (isset($result->response)) {
                $task = $this->task;
                $task->status = AutopostingTasks::STATUS_POSTED;
                $this->task->posted_at = humanDate(time());
                $task->save();
            }
        }else{
            $this->task->status = AutopostingTasks::STATUS_FAILED;
            $this->task->save();
            \Yii::warning("АVK P Отсутствует токен для группы ". $this->task->socialNetworksGroup->group_id, "DEBUG");
            TelegrammLoging::send('Отсутствует токен для группы ' . $this->task->socialNetworksGroup->group_id);
            Mailer::send(\Yii::$app->params['debugEmail'], "Отсутствие VK токена", 'api-error', ['error' => "Не найден токен доступа для группы ID ".$this->task->socialNetworksGroup->group_id]);
        }
    }

    private function uploadPhotos($album_id){
        $api_request_get_server = str_replace('{endpoint:key}', self::ENDPOINT_PHOTOS_UPLOAD_SERVER, $this->api_url);
        $api_request_get_server .= '&album_id='.$album_id.'&group_id='.$this->task->socialNetworksGroup->group_id;
        $api_request_get_server_response = json_decode(file_get_contents($api_request_get_server));
        if(isset($api_request_get_server_response->error)){
            \Yii::warning("АVK UP Ошибка получения сервера для загрузки фото ".$api_request_get_server_response->error->error_msg, "DEBUG");
            TelegrammLoging::send('<p>Ошибка получения сервера для загрузки фото для группы '.$this->task->socialNetworksGroup->group_id.'</p><br/><code>'.$api_request_get_server_response->error->error_msg.'</code><br/>'.$api_request_get_server);
            Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API VK.COM", 'api-error', ['error' => $api_request_get_server_response->error->error_msg, 'request' => $api_request_get_server, 'message' => 'Ошибка получения сервера для загрузки фото', 'details' => 'Произошла ошибка при получении сервера для загрузки фотографий для <a href="'.$this->task->socialNetworksGroup->url.'">сообщества</a>. <a href="https://vk.com/dev/photos.getUploadServer">Документация по вызванному методу</a>']);
            return;
        }
        $idx = 1;
        $file_list = [];
        foreach ($this->task->ad->files as $file){
            if(($file->ext->ext == Files::JPG_EXT OR $file->ext->ext == Files::JPEG_EXT OR $file->ext->ext == Files::PNG_EXT OR $file->ext->ext == Files::GIF_EXT) AND getimagesize($file->getFilePathWithoutExt())){// файлы других форматов vk api не принимает
                $image_size = getimagesize($file->getFilePathWithoutExt());
                if(filesize($file->getFilePathWithoutExt()) < 50000000
                    AND ($image_size[0]+$image_size[1] < 14000)
                    AND ($image_size[1]/$image_size[0] > 0.05 OR $image_size[0]/$image_size[1] > 0.05)
                    AND $idx < 6){//можно загрузить не более 5-и файлов, файлы больше 50мб, с суммой сторон больше 14000px и соотношением сторон более 1/20 vk api не принимает
                    $file_list['file'.$idx] = new \CURLFile($file->getFilePathWithoutExt(), $file->ext->mime, $file->hash.".".$file->ext->ext);
                    ++$idx;
                }
            }
        }
        if(!empty($file_list)) {
            \Yii::warning("АVK UP Фото для публикации в вк ".count($file_list), "DEBUG");
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
                    TelegrammLoging::send('<p>Ошибка сохранения фото в альбоме для группы '.$this->task->socialNetworksGroup->group_id.'</p><br/><code>'.$api_save_photos_response->errorr->error_msg.'</code><br/>'.$api_request_save_photos);
                    \Yii::warning("АVK UP Ошибка сохранения фото в альбоме ", "DEBUG");
                    Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API VK.COM", 'api-error', ['error' => $api_save_photos_response->error->error_msg, 'request' => $api_request_get_server_response->response->upload_url, 'message' => 'Ошибка сохранения фото в альбоме', 'details' => 'Произошла ошибка загрузки фотo в альбом <a href="'.$this->task->socialNetworksGroup->url.'">сообщества</a>. <a href="https://vk.com/dev/photos.save">Документация по вызванному методу</a>']);
                }else if(isset($api_save_photos_response->response)){
                    return $api_save_photos_response->response;
                }
            }else{
                $this->task->status = AutopostingTasks::STATUS_FAILED;
                $this->task->save();
                TelegrammLoging::send('<p>Ошибка CURL запроса во время загрузки фотографий '.$this->task->socialNetworksGroup->group_id.'</p><br/>'.$api_request_get_server_response->response->upload_url);
                \Yii::warning("АVK UP Ошибка CURL запроса во время загрузки фотографий", "DEBUG");
                Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API VK.COM", 'api-error', ['error' => "Ошибка CURL запроса во время загрузки фотографий", 'request' => $api_request_get_server_response->response->upload_url, 'message' => 'Ошибка CURL запроса во время загрузки фотографий']);
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
        $get_albums_response = $this->getAllAlbums();
        if(!$get_albums_response){
            return null;
        }
        $album_id = null;
        if($get_albums_response->response->count == 0){
            $album_created_response = $this->createAlbum();
            if($album_created_response){
                $album_id = $album_created_response->response->id;
            }
        }else{
            foreach($get_albums_response->response->items as $item){
                if($item->size < self::VK_PHOTOS_LIMIT_PER_ALBUM){
                    return $item->id;
                }
            }
            //если мы уже дожли сюда, то нужно создавать новый альбом
            $create_album_response = $this->createAlbum();
            if($create_album_response) {
                $album_id = $create_album_response->response->id;
            }
        }
        return $album_id;
    }

    /** Возвращаем все альбомы сообщества, если что-то не так - null
     * @return mixed|null
     */
    private function getAllAlbums(){
        $api_request_get_albums = str_replace('{endpoint:key}', self::ENDPOINT_GET_ALBUMS, $this->api_url);
        $api_request_get_albums .= '&owner_id=-'.$this->task->socialNetworksGroup->group_id;
        $response = json_decode(file_get_contents($api_request_get_albums));
        if(isset($response->error)){
            TelegrammLoging::send('<p>AVK GAA Ошибка извлечения альбомов для группы '.$this->task->socialNetworksGroup->group_id.'</p><br/><code>'.$response->error->error_msg.'</code><br/>'.$api_request_get_albums);
            \Yii::warning("АVK GAA Ошибка извлечения альбомов ".$response->error, "DEBUG");
            return null;
        }
        return $response;
    }

    /** Создаем новый альбом в сообществе, если что-то идет не так - возвращаем null
     * @return mixed|null
     */
    private function createAlbum(){
        $api_request_create_album = str_replace('{endpoint:key}', self::ENDPOINT_CREATE_ALBUM, $this->api_url);
        $api_request_create_album .= '&group_id='.$this->task->socialNetworksGroup->group_id.'&title="Объявления '.time().'"&upload_by_admins_only=1&comments_disabled=1&';
        $response = json_decode(file_get_contents($api_request_create_album));
        if(isset($response->error)){
            TelegrammLoging::send('<p>АVK CA Ошибка создания альбома для группы '.$this->task->socialNetworksGroup->group_id.'</p><br/><code>'.$response->error->error_msg.'</code><br/>&group_id='.$this->task->socialNetworksGroup->group_id.'&title="Объявления"&upload_by_admins_only=1&comments_disabled=1&');
            \Yii::warning("АVK CA Ошибка создания альбома ".$response->error, "DEBUG");
            return null;
        }
        return $response;
    }
}