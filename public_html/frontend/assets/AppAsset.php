<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/custom.css',
    ];
    public $js = [
        'js/selectpicker.js'
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
        'common\assets\CommonAsset',
        'frontend\assets\SelectpickerAsset',

    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];

    public $publishOptions = [
        'forceCopy'=>true,
      ];

}
