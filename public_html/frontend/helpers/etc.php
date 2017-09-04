<?php
function __($str, $array = []){
    return \Yii::t('app', $str, $array);
}