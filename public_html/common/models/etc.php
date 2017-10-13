<?php
function __($str, $array = []){
    return \Yii::t('app', $str, $array);
}

/** Обрезает строку до длины $limit и добавляет многоточие если строка обрезана
 * @param $str, входная строка
 * @param $limit, лимит символов до которого строка не обрезяется
 * @return string
 */
function cutText($str, $limit){
    if(strlen($str) > $limit){
        $str = mb_substr($str, 0, $limit);
        $str .= '...';
    }
    return $str;
}