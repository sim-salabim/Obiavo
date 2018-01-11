<?php
use backend\widgets\Form;

$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Текстовые данные',
            'attributes' => [
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$placement->_text],
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'Seo URL','model'=>$placement->_text],
            ]
        ]
    ],
];

echo Form::widget($items);
?>