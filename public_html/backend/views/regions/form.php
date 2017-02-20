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
                  ['name' => 'domain','type' => Form::INPUT_TEXT,'label' => 'Домен','model'=>$region],
                  ['name' => 'active','type' => Form::INPUT_CHECKBOX_INACTIVE,'label'=>'активность','model' => $region],
            ]
        ],
        [
            'panel-title' => 'Тексты',
            'attributes' => [
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$region->_mttext],
                ['name' => 'name_rp','type' => Form::INPUT_TEXT,'label' => 'Название в родительном падеже','model'=>$region->_mttext],
                ['name' => 'name_pp','type' => Form::INPUT_TEXT,'label' => 'Название в предложном падеже','model'=>$region->_mttext],
            ]
        ],
    ]
];

echo Form::widget($items);