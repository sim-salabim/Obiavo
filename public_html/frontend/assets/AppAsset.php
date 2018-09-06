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
        'frontend\assets\BootstrapAsset',
        'frontend\assets\JqueryAsset',
        'frontend\assets\SelectAsset',
        'frontend\assets\FontAwesomeAsset',
        'frontend\assets\SiteAsset',
        'frontend\assets\DropAsset',
    ];

    public $publishOptions = [
        'forceCopy'=>true,
      ];

}
