<?php
use yii\helpers\Url;
use yii\bootstrap\Html;

$form = [
    [
        'panel-title' => 'Основныe данные',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'domain:text:Домен', 'model' => $country],
            ['attributes' => 'active:checkbox:Активность', 'model' => $country],
        ]
    ],
    [
        'panel-title' => 'Тексты',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'name:text:Название', 'model' => $countryText],
            ['attributes' => 'name_rp:text:Название в родительном падеже', 'model' => $countryText],
            ['attributes' => 'name_pp:text:Название в предложном падеже', 'model' => $countryText],
        ]
    ],
];

$saveUrl = $toUrl;

echo $this->render('/templates/form',compact('form','saveUrl'));