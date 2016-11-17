<?php
use yii\helpers\Url;
use yii\bootstrap\Html;

$form = [
    [
        'model' => $region,
        'panel-title' => 'Основныe данные',
        'columns' => [
            // attribute:typeField:label
            'domain:text:Домен',
            'active:checkbox:Активность',
        ]
    ],
    [
        'model' => $regionText,
        'panel-title' => 'Тексты',
        'columns' => [
            // attribute:typeField:label
            'name:text:Название',
            'name_rp:text:Название в родительном падеже',
            'name_pp:text:Название в предложном падеже'
        ]
    ],
];

$saveUrl = $toUrl;

echo $this->render('/templates/form',compact('form','saveUrl'));