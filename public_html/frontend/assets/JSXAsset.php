<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class JSXAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [        
        
        'js/reactExample.jsx'
    ];
    
    public $jsOptions = ['type'=>'text/jsx'];

}
