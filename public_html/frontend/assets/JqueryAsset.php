<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class JqueryAsset extends AssetBundle
{
    public $sourcePath = '@vendor/components';

    public $js = [
        'jquery/jquery.min.js',
        'jqueryui/jquery-ui.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
    public $css = [
        'jqueryui/themes/base/jquery-ui.min.css',
    ];
}