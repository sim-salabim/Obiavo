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
                  ['name' => 'longitude','type' => Form::INPUT_TEXT,'label'=>'Долгота','model' => $city, 'model_name' => 'Longitude'],
                  ['name' => 'latitude','type' => Form::INPUT_TEXT,'label'=>'Широта','model' => $city, 'model_name' => 'Latitude'],
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