<?php
namespace frontend\helpers;

class TransliterationHelper {

    public static function transliterateToCyrillic($str){
        $cyr = [
            'Край', 'край','ч','ш','Ч','ц','ч','щ','ю','я','Ё','Ж','Ц','Ч','Ш','Щ','Ю','Я','а','б','в','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ъ','ы','и','э',
            'А','Б','В','В','Г','Д','Е','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ъ','Ы','Ь','Э','к','К'
        ];
        $lat = [
            'Chry', 'chry','ch','sh','Ch','ts','ch','sht','yu','ya','Io','Zh','Ts','Ch','Sh','Sht','Yu','Ya','a','b','v','w','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','a','i','y','e',
            'A','B','V','W','G','D','E','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','A','I','Y','e','q','Q'
        ];
        $textlat = str_replace($lat, $cyr, $str);
        return $textlat;
    }

    public static function transliterate($str, $str_to_lower = true, $backspaces = false)
    {
        // ГОСТ 7.79B
        $transliteration = array(
            'А' => 'A', 'а' => 'a',
            'Б' => 'B', 'б' => 'b',
            'В' => 'V', 'в' => 'v',
            'Г' => 'G', 'г' => 'g',
            'Д' => 'D', 'д' => 'd',
            'Е' => 'E', 'е' => 'e',
            'Ё' => 'Yo', 'ё' => 'yo',
            'Ж' => 'Zh', 'ж' => 'zh',
            'З' => 'Z', 'з' => 'z',
            'И' => 'I', 'и' => 'i',
            'Й' => 'J', 'й' => 'j',
            'К' => 'K', 'к' => 'k',
            'Л' => 'L', 'л' => 'l',
            'М' => 'M', 'м' => 'm',
            'Н' => "N", 'н' => 'n',
            'О' => 'O', 'о' => 'o',
            'П' => 'P', 'п' => 'p',
            'Р' => 'R', 'р' => 'r',
            'С' => 'S', 'с' => 's',
            'Т' => 'T', 'т' => 't',
            'У' => 'U', 'у' => 'u',
            'Ф' => 'F', 'ф' => 'f',
            'Х' => 'H', 'х' => 'h',
            'Ц' => 'C', 'ц' => 'c',
            'Ч' => 'Ch', 'ч' => 'ch',
            'Ш' => 'Sh', 'ш' => 'sh',
            'Щ' => 'Shh', 'щ' => 'shh',
            'Ъ' => '', 'ъ' => '',
            'Ы' => 'Y`', 'ы' => 'y`',
            'Ь' => '', 'ь' => '',
            'Э' => 'E`', 'э' => 'e',
            'Ю' => 'Yu', 'ю' => 'yu',
            'Я' => 'Ya', 'я' => 'ya',
            '’' => '', 'ˮ' => '',
        );

        if($backspaces){
            $transliteration[' '] = ' ';
        }else{
            $transliteration[' '] = '-';
        }

        $str = strtr($str, $transliteration);
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/[^0-9a-z\-]/', '', $str);
        if(!$backspaces) {
            $str = preg_replace('|([-]+)|s', '-', $str);
        }
        $str = trim($str, '-');
        $str = ($str_to_lower) ? mb_strtolower($str) : $str;
        return $str;
    }
}