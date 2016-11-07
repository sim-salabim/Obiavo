<?php

namespace common\helpers;

use yii\helpers\Json;

/**
 * Class JsonData
 * @package common\helpers
 * Отдает клиенту правильный Json
 */
class JsonData
{

    const NOTHING                         = 'nothing';
    const REFRESHPAGE                     = 'refreshpage';
    const RELOADPAGE                      = 'reloadpage';
    const REDIRECT                        = 'redirect';
    const ERROR                           = 'error';
    const COMPLETED                       = 'completed';
    const NOTICE                          = 'notice';
    const WARNING                         = 'warning';
    const LOADCONTENT                     = 'loadcontent';

    private static $jsonData = [];

    public function set($jsonDataArray){
        $json = [];

        foreach ($jsonDataArray as $constOperationName => $jsonData) {
            $json['type'] = $constOperationName;
            $json['data'] = $jsonData;
        }

        self::$jsonData[] = $json;
    }

    public function get(){
        return self::$jsonData;
    }
}
