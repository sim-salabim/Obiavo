<?php
namespace frontend\helpers;
/* 
 * Здесь будут функции-помощники для работы с текстом
 */


class TextHelper {
    
    /**
    * Вырезать расширение из названия файла
    * @param string $path путь к файлу    
    * @return string
    */
    public static function exclude_ext($path) {
       return mb_substr($path, 0, mb_strrpos($path, '.'));
    }
    
    /**
    * Преобразовть слеши в двойную черточку
    * @param string $str
    */
    public static function dash2slash($str) {
        return str_replace('/', '--', $str);
    }

}
