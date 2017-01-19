<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class AjaxSelectpicker extends AssetBundle
{
    public $sourcePath = '@vendor/bower/ajax-bootstrap-select/dist';
    public $css = [
        'css/ajax-bootstrap-select.min.css',
    ];
    public $js = [
        'js/ajax-bootstrap-select.js',
    ];
}
