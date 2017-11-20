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
                  ['name' => 'domain','type' => Form::INPUT_TEXT,'label' => 'Домен','model'=>$city],
                  ['name' => 'active','type' => Form::INPUT_CHECKBOX,'label'=>'Активность','model' => $city, 'model_name' => 'City'],
                  ['name' => 'show_on_site','type' => Form::INPUT_CHECKBOX, 'label'=>'Отображение на главной','model' => $city, 'model_name' => 'City'],
            ]
        ],
        [
            'panel-title' => 'Тексты',
            'attributes' => [
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$city->_text],
                ['name' => 'name_rp','type' => Form::INPUT_TEXT,'label' => 'Название в родительном падеже','model'=>$city->_text],
                ['name' => 'name_pp','type' => Form::INPUT_TEXT,'label' => 'Название в предложном падеже','model'=>$city->_text],
            ]
        ],
    ]
];

echo Form::widget($items);