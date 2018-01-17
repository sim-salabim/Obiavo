<?php
use backend\widgets\Form;

$current_value = [];
$current_value['id'] = (isset($sn->default_group_id) and $sn->default_group_id) ? $sn->default_group_id : null;
$current_value['title'] = (isset($sn->default_group_id) and $sn->default_group_id) ? $sn->default->name : null;
$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                  ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$sn],
                  ['name' => 'default_group_id','type' => Form::SEARCH_AUTOCOMPLETE,'label' => 'Группа по умолчанию', 'model_name' => 'SocialNetworks', 'model'=>$sn, 'url' => 'sn-groups/search', 'placeholder' => 'Начните печатать название группы...', 'current_value' => $current_value, 'input_id' => 'socialnetworks-default_group_id'],
                ['name' => 'active','type' => Form::INPUT_CHECKBOX,'label' => 'Активность','model'=>$sn, 'model_name' => 'SocialNetworks'],
            ]
        ]
    ]
];

echo Form::widget($items);
?>