<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class SelectpickerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower-asset/bootstrap-select/dist';
    public $css = [
        'css/bootstrap-select.min.css',
    ];
    public $js = [
        'js/bootstrap-select.js',
    ];
}
