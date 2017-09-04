<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class SelectAsset extends AssetBundle
{
    public $sourcePath = '@vendor/kartik-v/yii2-widget-select2/assets';

    public $css = [
        'css/select2.min.css'
    ];
    public $js = [
        'js/select2.full.min.js'
    ];

}