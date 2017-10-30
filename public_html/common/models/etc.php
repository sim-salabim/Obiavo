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
function make_thumb($src, $dest, $desired_width) {
    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);
    $desired_height = floor($height * ($desired_width / $width));
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
    imagejpeg($virtual_image, $dest);
}