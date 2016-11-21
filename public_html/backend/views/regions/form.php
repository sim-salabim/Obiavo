<?php
use yii\helpers\Url;
use yii\bootstrap\Html;

$form = [
    [
        'panel-title' => 'Основныe данные',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'domain:text:Домен', 'model' => $region],
            ['attributes' => 'active:checkbox:Активность', 'model' => $region],
        ]
    ],
    [
        'panel-title' => 'Тексты',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'name:text:Название', 'model' => $regionText],
            ['attributes' => 'name_rp:text:Название в родительном падеже', 'model' => $regionText],
            ['attributes' => 'name_pp:text:Название в предложном падеже', 'model' => $regionText],
        ]
    ],
];

$saveUrl = $toUrl;

echo $this->render('/templates/form',compact('form','saveUrl'));