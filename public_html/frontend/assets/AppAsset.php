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
    ];
    public $js = [
        'js/main.js',
        'js/init.js',
        'js/selectpicker.js',
    ];
    public $depends = [
        'frontend\assets\BootstrapAsset',
        'frontend\assets\Fontawesome',
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
