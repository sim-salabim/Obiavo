<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use backend\widgets\Form;

$items = [
    'saveUrl' => Url::to(''),
    'rows' => [
        [
            'panel-title' => 'Текстовые данные',
            'attributes' => [
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$regionText],
                ['name' => 'name_rp','type' => Form::INPUT_TEXT,'label' => 'Название в родительном падеже','model'=>$regionText],
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название в предложном падеже','model'=>$regionText],
            ]
        ],
    ]
];

echo Form::widget($items);