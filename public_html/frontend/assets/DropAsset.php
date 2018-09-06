<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class DropAsset extends AssetBundle
{
    public $sourcePath = '@vendor/perminder-klair/yii2-dropzone/bower_components/dropzone';

    public $css = [
        'dist/dropzone.css'
    ];
    public $js = [
        'dist/dropzone.js'
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}