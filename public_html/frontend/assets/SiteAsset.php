<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class SiteAsset extends AssetBundle
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

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];

}