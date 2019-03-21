<?php
function __($str, $array = []){
    return \Yii::t('app', $str, $array);
}

/**  Обрезает строку до длины $limit и добавляет многоточие если строка обрезана
 * @param $str, входная сторка
 * @param $limit
 * @param bool $dots, вставлять ли многоточие в конце
 * @return string
 */
function cutText($str, $limit, $dots = true){
    if(strlen($str) > $limit){
        $str = mb_substr($str, 0, $limit);
        if($dots) {
            $str .= '...';
        }
    }
    return $str;
}

/**
 * @param $amount, int - количество
 * @param $array, array - ['книга', 'книги', 'книг']
 * @return string
 */
function countString($amount, $array){
    $_amount = ((int)$amount) % 100;
    if ($_amount>=11 && $_amount<=19) {
        $ending = $array[2];
    }
    else {
        $i = $_amount % 10;
        switch ($i)
        {
            case (1): $ending = $array[0]; break;
            case (2):
            case (3):
            case (4): $ending = $array[1]; break;
            default: $ending = $array[2];
        }
    }
    return ((int)$amount) .' ' . $ending;

}

/**  Делаем превьюху картинки
 * @param $src, источник
 * @param $dest, назначение
 * @param $desired_width, желаемая ширина
 */
function make_thumb($src, $dest, $desired_width, $ext) {
    $source_image = null;
    switch($ext){
        case \common\models\Files::JPG_EXT :
            $source_image = imagecreatefromjpeg($src);
            break;
        case \common\models\Files::JPEG_EXT :
            $source_image = imagecreatefromjpeg($src);
            break;
        case \common\models\Files::PNG_EXT :
            $source_image = imagecreatefrompng($src);
            break;
        case \common\models\Files::GIF_EXT :
            $source_image = imagecreatefromgif($src);
            break;
        default:
            $source_image = imagecreatefromjpeg($src);
    }

    $width = imagesx($source_image);
    $height = imagesy($source_image);
    $desired_height = floor($height * ($desired_width / $width));
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
    switch($ext){
        case \common\models\Files::JPG_EXT :
            imagejpeg($virtual_image, $dest);
            break;
        case \common\models\Files::JPEG_EXT :
            imagejpeg($virtual_image, $dest);
            break;
        case \common\models\Files::PNG_EXT :
            imagepng($virtual_image, $dest);
            break;
        case \common\models\Files::GIF_EXT :
            imagegif($virtual_image, $dest);
            break;
        default:
            imagejpeg($virtual_image, $dest);
    }
}

function humanDate($time){
    return date("Y-m-d H:i:s", $time);
}

function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}