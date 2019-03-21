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
                ['name' => 'application_url','type' => Form::INPUT_TEXT,'label' => 'Url подачи','model'=>$city->_mttext],
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$city->_mttext],
                ['name' => 'name_rp','type' => Form::INPUT_TEXT,'label' => 'Название в родительном падеже','model'=>$city->_mttext],
                ['name' => 'name_pp','type' => Form::INPUT_TEXT,'label' => 'Название в предложном падеже','model'=>$city->_mttext],
            ]
        ],
    ]
];

echo Form::widget($items);