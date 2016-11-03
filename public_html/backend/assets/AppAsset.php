<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
    ];
    public $depends = [
        'common\assets\CommonAsset'
    ];

    /**
     * Подключаем js в заголовках, чтобы не было проблем с выполнение js кода
     * в php файлах
     */

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];

    public $publishOptions = [
        'forceCopy'=>true,
    ];
}
