<?php
use backend\widgets\Form;

$active_networks = \common\models\SocialNetworks::find()->where(['active' => 1])->all();
$sn_group_model = new \common\models\SocialNetworksGroups();
$sn_inputs = [];
$default_groups = $main_group->defaultGroups;
$default_groups_values = [];
if(count($default_groups)){
    foreach ($default_groups as $group){
        $default_groups_values[$group->socialNetwork->id] = ['id'=>$group->id, 'title'=>$group->name];
    }
}
if(count($active_networks)){
    foreach ($active_networks as $snt){
        $current_value = [];
        if(isset($default_groups_values[$snt->id])){
            $current_value = $default_groups_values[$snt->id];
        }else{
            $current_value = ['id'=>'', 'title'=>''];
        }
        $sn_inputs[] = ['name' => "id",'type' => Form::SEARCH_AUTOCOMPLETE,'label' => 'Группа для '.$snt->name, 'model_name' => "DefaultGroups[]", 'model'=>$sn_group_model, 'url' => 'sn-groups/search?sn_id='.$snt->id, 'placeholder' => 'Начните печатать название группы...', 'current_value' => 1, 'input_id' => uniqid(), 'current_value' => $current_value];
    }
}

$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                  ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$main_group],
                  ['name' => 'as_default','type' => Form::INPUT_CHECKBOX,'label' => 'Использовать по умолчанию', 'model_name'=> 'SocialNetworksGroupsMain','model'=>$main_group],
            ],
        ],
        [
            'panel-title' => 'Сообщества по умолчанию',
            'attributes' =>
                $sn_inputs
        ],
    ]
];

echo Form::widget($items);