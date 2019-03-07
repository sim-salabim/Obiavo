<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use backend\widgets\Form;

$items = [
    'saveUrl' => Url::to(''),
    'rows' => [
        [
            'panel-title' => "{$placement->_text->name} - {language}",
            'attributes' => [
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$placement->_mttext],
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'Url','model'=>$placement->_mttext],
                ['name' => 'apply_url','type' => Form::INPUT_TEXT,'label' => 'Url Подачи','model'=>$placement->_mttext],
            ]
        ],
    ]
];

echo Form::widget($items);