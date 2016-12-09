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
//        'https://unpkg.com/react@15/dist/react.min.js',
//        'https://unpkg.com/react-dom@15/dist/react-dom.min.js',
//        '//fb.me/JSXTransformer-0.13.1.js',
        
        'js/main.js',
        'js/init.js',
        'js/selectpicker.js',
    ];
    public $depends = [        
        'yii\bootstrap\BootstrapPluginAsset',        
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
