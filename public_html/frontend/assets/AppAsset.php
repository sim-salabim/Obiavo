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

    public $depends = [
        'frontend\assets\SiteAsset',
        'frontend\assets\BootstrapAsset',
        'frontend\assets\Fontawesome',
        'frontend\assets\SelectpickerAsset',
    ];

    public $publishOptions = [
        'forceCopy'=>true,
      ];

}
