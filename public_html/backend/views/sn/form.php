<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use backend\widgets\Form;

$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                  ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$sn],
                  ['name' => 'default_group_id','type' => Form::INPUT_TEXT,'label' => 'Группа по умолчанию','model'=>$sn],
            ]
        ]
    ]
];

echo Form::widget($items);