<?php
use backend\widgets\Form;

$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                  ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$sn],
                  ['name' => 'default_group_id','type' => Form::SEARCH_AUTOCOMPLETE,'label' => 'Группа по умолчанию', 'model_name' => 'SocialNetworks', 'model'=>$sn, 'url' => 'categories/search'],
            ]
        ]
    ]
];

echo Form::widget($items);