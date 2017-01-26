<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use backend\widgets\Form;

$items = [
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
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$regionText],
                ['name' => 'name_rp','type' => Form::INPUT_TEXT,'label' => 'Название в родительном падеже','model'=>$regionText],
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название в предложном падеже','model'=>$regionText],
            ]
        ],
    ]
];

echo Form::widget($items);

//$saveUrl = $toUrl;

//echo $this->render('/templates/form',compact('form','saveUrl'));