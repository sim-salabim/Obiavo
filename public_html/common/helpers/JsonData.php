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
    const SUCCESSMESSAGE                  = 'success';
    const RELOADPAGE                      = 'reloadpage';
    const REDIRECT                        = 'redirect';
    const ERROR                           = 'error';
    const COMPLETED                       = 'completed';
    const NOTICE                          = 'notice';
    const WARNING                         = 'warning';
    const LOADCONTENT                     = 'loadcontent';
    const GENERATEPASSWORD                = 'generatepassword';
    const SHOW_VALIDATION_ERRORS_INPUT    = 'show_validation_errors_input';

    private static $jsonData = [];

    public function current($jsonDataArray){
        $json = [];

        foreach ($jsonDataArray as $constOperationName => $jsonData) {
            $json['type'] = $constOperationName;
            $json['data'] = $jsonData;
            self::$jsonData[] = $json;
        }

        return self::$jsonData;
    }
}
