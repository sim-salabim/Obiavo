<?php
namespace common\models\libraries;

use common\models\AutopostingTasks;
use common\models\Mailer;
use common\models\Settings;

class AutopostingOk {

    private $task;
    private $group;
    private $access_token;
    private $secret_key;
    private $public_key;

    function __construct(AutopostingTasks $task){
        $this->task = $task;
        $this->group = $this->task->socialNetworksGroup;
        $access_token = null;
        $settings = Settings::find()->one();
        if($this->group->token){
            $access_token = $this->group->token;
        }else{
            if($settings AND $settings->ok_token){
                $access_token = $settings->ok_token;
            }
        }
        $secret_key = null;
        if($this->group->consumer_secret){
            $secret_key = $this->group->consumer_secret;
        }else{
            if($settings AND $settings->ok_secret_key){
                $secret_key = $settings->ok_secret_key;
            }
        }
        $public_key = null;
        if($this->group->consumer_key){
            $public_key = $this->group->consumer_key;
        }else{
            if($settings AND $settings->ok_public_key){
                $public_key = $settings->ok_public_key;
            }
        }
        $this->access_token = $access_token;
        $this->secret_key = $secret_key;
        $this->public_key = $public_key;
    }

    function post(){
//        $images_str = "";
//        if(count($this->task->ad->files)) {
//            $images_arr = $this->uploadImages();
//            $images_str .= ',{"type": "photo","list":[';
//            foreach($images_arr as $id => $token){
//                $images_str .= '{"id":"'.$id.'"},';
//            }
//            $images_str = rtrim($images_str, ",");
//            $images_str .= ']}';
//        }
        $domain = $this->task->ad->city->region->country->domain;
        $link = "https://".$domain."/".$this->task->ad->url();
//        $link = "https://via.placeholder.com/728x600.png";//TODO убрать когда obiavo будет не запаролен
        $post_title = str_replace(["\n","\t","\v", "\r","\b","\f"],['\n','\t','\v','\r','\b','\b'],$this->task->ad->title);
        $post_text = str_replace(["\n","\t","\v","\r","\b","\f"],['\n','\t','\v','\r','\b','\b'],$this->task->ad->text);

        $params = array(
            "application_key"=>$this->public_key,
            "method"=>"mediatopic.post",
            "gid" => $this->group->group_id,
            "type"=>"GROUP_THEME",
            "attachment"=>'{"media":[
             {
                  "type": "link",
                  "title": "'.$post_title.' \nЦена: '.$this->task->ad->price.'",
                  "url": "'.$link.'"
                }
                    ]}',
            "format"=>"json"
        );
        $sig = md5($this->arInStr($params).md5("{$this->access_token}{$this->secret_key}"));
        $params["access_token"] = $this->access_token;
        $params["sig"]=$sig;
        $result = json_decode($this->getUrl("https://api.ok.ru/fb.do", "POST", $params), true);
//Если парсер не смог открыть нашу ссылку (иногда он это делает со второй попытки), то отправляем ещё раз
        if (isset($result['error_code'])) {
            \Yii::warning('АO P Ошибка открытия ссылки парсером, идем на 2-ю попытку, задача '.$this->task->id, "DEBUG");
            sleep(5);
            $result = json_decode($this->getUrl("https://api.ok.ru/fb.do", "POST", $params), true);
            if (isset($result['error_code'])){
                $this->task->status = AutopostingTasks::STATUS_FAILED;
                $this->task->save();
                \Yii::warning('АO P Ошибка открытия ссылки парсером со второй попытки, задача '.$this->task->id, "DEBUG");
            }else{
                $this->task->status = AutopostingTasks::STATUS_POSTED;
                $this->task->save();
                \Yii::warning('АO P Открытие ссылки парсером со второй попытки, задача '.$this->task->id, "DEBUG");
            }
        }else{
            $this->task->status = AutopostingTasks::STATUS_POSTED;
            $this->task->save();
            \Yii::warning('АO P Открытие ссылки парсером со первой попытки, задача '.$this->task->id, "DEBUG");
        }
    }

    private function uploadImages(){
        $get_albums_params = array(
            "application_key"=>$this->public_key,
            "method"=>"photos.getAlbums",
            "gid" => $this->group->group_id,
        );
        $album_id = null;
        $sig = md5($this->arInStr($get_albums_params).md5("{$this->access_token}{$this->secret_key}"));
        $get_albums_params["access_token"] = $this->access_token;
        $get_albums_params["sig"]=$sig;
        $get_album_result = json_decode($this->getUrl("https://api.ok.ru/fb.do", "POST", $get_albums_params), true);
        if(isset($get_album_result['error_code'])){
            TelegrammLoging::send('Ошибка получения списка альбомов ОК.РУ ID сообщества: '.$this->task->socialNetworksGroup->id.' error_code: '.$get_album_result['error_code'].', message: '.$get_album_result['error_msg'] );
            Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API OK.RU", 'api-error', ['message' =>$get_album_result['error_msg'].' '.$get_album_result['error_code']]);
            return false;
        }else{
            if(isset($get_album_result['albums']) AND !count($get_album_result['albums'])) {
                $add_album_params = [
                    "application_key" => $this->public_key,
                    "method" => "photos.createAlbum",
                    "gid" => $this->group->group_id,
                    "title" => 'Альбом ' . $this->group->name
                ];
                $sig = md5($this->arInStr($add_album_params) . md5("{$this->access_token}{$this->secret_key}"));
                $add_album_params["access_token"] = $this->access_token;
                $add_album_params["sig"] = $sig;
                $add_album_result = json_decode($this->getUrl("https://api.ok.ru/fb.do", "POST", $add_album_params), true);
                if(isset($add_album_result['error_code'])){
                    TelegrammLoging::send('Ошибка добавления альбома ОК.РУ ID сообщества: '.$this->task->socialNetworksGroup->id.' error_code: '.$add_album_result['error_code'].', message: '.$add_album_result['error_msg'] );
                    Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API OK.RU", 'api-error', ['message' =>$add_album_result['error_msg'].' '.$get_album_result['error_code']]);
                    return false;
                }else{
                    $album_id = $add_album_result;
                }
            }else{
                $album_id = $get_album_result['albums'][0]['aid'];
            }
        }
        if(!$album_id){
            return false;
        }else{
            $begining_adding_photos_params = [
                "application_key" => $this->public_key,
                "method" => "photosV2.getUploadUrl",
                "gid" => $this->group->group_id,
                "aid" => $album_id,
                "title" => 'Альбом ' . $this->group->name,
                "count" => count($this->task->ad->files)
            ];
            $sig = md5($this->arInStr($begining_adding_photos_params) . md5("{$this->access_token}{$this->secret_key}"));
            $begining_adding_photos_params["access_token"] = $this->access_token;
            $begining_adding_photos_params["sig"] = $sig;
            $begining_adding_photos_result = json_decode($this->getUrl("https://api.ok.ru/fb.do", "POST", $begining_adding_photos_params), true);
            if(isset($begining_adding_photos_result['error_code'])){
                TelegrammLoging::send('Ошибка загрузки фото в альбом ОК.РУ ID сообщества: '.$this->task->socialNetworksGroup->id.' error_code: '.$begining_adding_photos_result['error_code'].', message: '.$begining_adding_photos_result['error_msg'] );
                Mailer::send(\Yii::$app->params['debugEmail'], "Ошибка API OK.RU", 'api-error', ['message' =>$begining_adding_photos_result['error_msg'].' '.$get_album_result['error_code']]);
                return false;
            }else{
                $files = $this->task->ad->files;
                $fields = [];
                $filenames = [];
                $files_to_upload = [];
                foreach($files as $k => $file){
                    $fields['pic'.$k] = $file->name;
                    $filenames[] = $file->getFilePathWithoutExt();
                    $files_to_upload[$file->getFilePathWithoutExt()] = file_get_contents($file->getFilePathWithoutExt());
                }
                $url = $begining_adding_photos_result['upload_url'];
                $curl = curl_init();
                $boundary = uniqid();
                $delimiter = '-------------' . $boundary;
                $post_data = $this->build_data_files($boundary, $fields, $files_to_upload);
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => $post_data,
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: multipart/form-data; boundary=" . $delimiter,
                        "Content-Length: " . strlen($post_data)
                    ),
                ));
                $result = json_decode(curl_exec($curl));
                if(isset($result->photos) and count($result->photos)){
                    $response = [];
                    foreach($result->photos as $photo_id => $token){
                        $response[$photo_id] = $token;
                    }
                    return $response;
                }else{
                    return false;
                }
            }
        }
    }

    private function getUrl($url, $type="GET", $params=array(), $timeout=30) {
        if ($ch = curl_init()) {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            if ($type == "POST") {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        } else {
            return "{}";
        }
    }

    private function arInStr($array) {
        ksort($array);
        $string = "";
        foreach($array as $key=>$val) {
            if (is_array($val)) {
                $string .= $key."=".$this->arInStr($val);
            } else {
                $string .= $key."=".$val;
            }
        }
        return $string;
    }

    function build_data_files($boundary, $fields, $files){
        $data = '';
        $eol = "\r\n";

        $delimiter = '-------------' . $boundary;

        foreach ($fields as $name => $content) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
                . $content . $eol;
        }


        foreach ($files as $name => $content) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $name . '"' . $eol
                //. 'Content-Type: image/png'.$eol
                . 'Content-Transfer-Encoding: binary'.$eol
            ;

            $data .= $eol;
            $data .= $content . $eol;
        }
        $data .= "--" . $delimiter . "--".$eol;


        return $data;
    }
}