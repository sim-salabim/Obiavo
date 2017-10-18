<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use backend\widgets\Form;

$items = [
    'saveUrl' => Url::to(''),
    'rows' => [
        [
            'panel-title' => 'Текстовые данные - {language}',
            'attributes' => [

                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Title','model'=>$cms->_mttext],
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'URL','model'=>$cms->_mttext],
                ['name' => 'text','type' => Form::INPUT_TEXT,'label' => 'Text','model'=>$cms->_mttext],
            ]
        ],
    ]
];

echo Form::widget($items);