<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/rmrevin';

    public $css = [
        'yii2-fontawesome/assets/css/font-awesome.min.css',
    ];
}