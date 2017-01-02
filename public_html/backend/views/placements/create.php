<?php
use yii\helpers\Url;

$form = [
    [
        'panel-title' => 'Текстовые данные',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'name:text:Название', 'model' => $placement->_text],
        ]
    ],
];

$saveUrl = $toUrl;

echo $this->render('/templates/form',compact('form','saveUrl'));