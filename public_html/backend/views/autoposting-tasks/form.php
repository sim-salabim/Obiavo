<?php
use backend\widgets\Form;
$current_ad_value = [];
$current_ad_value['id'] = ($task->ads_id) ? $task->ads_id : null;
$current_ad_value['title'] = ($task->ads_id) ? $task->ad->title : null;
$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                ['name' => 'ads_id','type' => Form::SEARCH_AUTOCOMPLETE,'url'=>'ads/search-active','label' => 'Объявление','model'=>$task, 'model_name' => 'AutopostingTasks', 'current_value'=>$current_ad_value,'input_id' => 'autopostingtasks-ads_id'],
                ['name' => 'social_networks_groups_id','type' => Form::SELECT,'label'=>'Активность','model' => $task, 'model_name' => 'AutopostingTasks', 'selected' => $task->id, 'options' => \common\models\SocialNetworks::getAllAsArrayForAutoposting()],
            ]
        ],
    ]
];

echo Form::widget($items);