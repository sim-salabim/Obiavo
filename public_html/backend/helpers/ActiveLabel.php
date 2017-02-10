<?php
namespace backend\helpers;
/*
 * Возвращает html label для поля актвности
 */

class ActiveLabel {

    private static $status = false;

    private static $text = [
        'active' => 'Active',
        'inactive' => 'In Active'
    ];

    private static $activeLabel = '<span class="label label-success pull-right" style="margin-left:10px">{text}</span>';

    private static $inactiveLabel = '<span class="label label-default pull-right">{text}</span>';

    /**
     *
     * @param text/boolean $status
     * @param array $message
     * @example array [
     *                  'active' => 'is active',
     *                  'inactive' => 'is not active'
     *                ]
     * @return text
     */
    public static function status($status,$message) {
        self::$status = $status;
        self::$text = $message;

        return preg_replace('/\{text\}/' , self::text(), self::label());
    }

    private static function label(){
        if (self::$status){
            return self::$activeLabel;
        }

        return self::$inactiveLabel;
    }

    private static function text(){
        if (self::$status && isset(self::$text['active'])){
            return self::$text['active'];
        }

        if (isset(self::$text['inactive']))
            return self::$text['inactive'];
    }
}

