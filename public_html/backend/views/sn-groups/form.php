<?php
use backend\widgets\Form;

$main_groups = \common\models\SocialNetworksGroupsMain::find()->all();
$main_groups_options = [];
if(!empty($main_groups)){
    foreach ($main_groups as $k => $main_group){
        $main_groups_options[$k]['id'] = $main_group->id;
        $main_groups_options[$k]['name'] = $main_group->name;
    }
}
$social_networks = \common\models\SocialNetworks::find()->all();
$social_networks_options = [];
if(!empty($social_networks)){
    foreach ($social_networks as $k => $sn){
        $social_networks_options[$k]['id'] = $sn->id;
        $social_networks_options[$k]['name'] = $sn->name;
    }
}
$current_city_value = [];
$current_city_value['id'] = ($sn_group->cities_id) ? $sn_group->cities_id : null;
$current_city_value['title'] = ($sn_group->cities_id) ? $sn_group->city->_text->name : null;
$current_region_value = [];
$current_region_value['id'] = ($sn_group->regions_id) ? $sn_group->regions_id : null;
$current_region_value['title'] = ($sn_group->regions_id) ? $sn_group->region->_text->name : null;
$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название группы','model'=>$sn_group],
                ['name' => 'social_networks_groups_main_id','type' => Form::SELECT, 'options' => $main_groups_options,'label' => 'Основная группа','model'=>$sn_group, 'model_name' => 'SocialNetworksGroups'],
                ['name' => 'social_networks_id','type' => Form::SELECT, 'options' => $social_networks_options,'label' => 'Соцсеть','model'=>$sn_group, 'model_name' => 'SocialNetworksGroups'],
                ['name' => 'cities_id','type' => Form::SEARCH_AUTOCOMPLETE,'label' => 'Город', 'model_name' => 'SocialNetworksGroups', 'model'=>$sn_group, 'url' => 'cities/search ', 'placeholder' => 'Начните печатать название города...', 'current_value' => $current_city_value],
                ['name' => 'regions_id','type' => Form::SEARCH_AUTOCOMPLETE,'label' => 'Регион', 'model_name' => 'SocialNetworksGroups', 'model'=>$sn_group, 'url' => 'regions/search ', 'placeholder' => 'Начните печатать название региона...', 'current_value' => $current_region_value],
                ['name' => 'code_sm','type' => Form::INPUT_AREA_TEXT,'label' => 'Маленький блок','model'=>$sn_group, 'model_name' => 'SocialNetworksGroups'],
                ['name' => 'code_md','type' => Form::INPUT_AREA_TEXT,'label' => 'Средний блок','model'=>$sn_group, 'model_name' => 'SocialNetworksGroups'],
                ['name' => 'code_lg','type' => Form::INPUT_AREA_TEXT,'label' => 'Большой блок','model'=>$sn_group, 'model_name' => 'SocialNetworksGroups'],
            ]
        ]
    ]
];

echo Form::widget($items);