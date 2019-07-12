<?php
namespace backend\assets;

use yii\web\AssetBundle;

class DropAsset extends AssetBundle
{
    public $sourcePath = '@vendor/perminder-klair/yii2-dropzone/bower_components/dropzone';

    public $css = [
        'dist/min/dropzone.min.css'
    ];
    public $js = [
        'dist/min/dropzone.min.js'
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
}