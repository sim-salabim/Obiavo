<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class GridAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/custom-grid.css',
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];

}