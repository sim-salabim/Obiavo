<?php
use yii\helpers\Url;
use yii\bootstrap\Html;

$form = [
    [
        'panel-title' => 'Основныe данные',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'domain:text:Домен', 'model' => $city],
            ['attributes' => 'active:checkbox:Активность', 'model' => $city],
        ]
    ],
    [
        'panel-title' => 'Тексты',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'name:text:Название', 'model' => $cityText],
            ['attributes' => 'name_rp:text:Название в родительном падеже', 'model' => $cityText],
            ['attributes' => 'name_pp:text:Название в предложном падеже', 'model' => $cityText],
        ]
    ],
];

$saveUrl = $toUrl;

echo $this->render('/templates/form',compact('form','saveUrl'));