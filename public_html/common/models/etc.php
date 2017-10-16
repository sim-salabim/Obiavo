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