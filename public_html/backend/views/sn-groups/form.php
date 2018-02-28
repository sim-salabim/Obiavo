<?php
use backend\widgets\Form;
use \common\models\SocialNetworksGroupsMain;
use \common\models\SocialNetworks;

$current_city_value = [];
$current_city_value['id'] = ($sn_group->cities_id) ? $sn_group->cities_id : null;
$current_city_value['title'] = ($sn_group->cities_id) ? $sn_group->city->_text->name : null;
$current_region_value = [];
$current_region_value['id'] = ($sn_group->regions_id) ? $sn_group->regions_id : null;
$current_region_value['title'] = ($sn_group->regions_id) ? $sn_group->region->_text->name : null;
$current_country_value = [];
$current_country_value['id'] = ($sn_group->countries_id) ? $sn_group->countries_id : null;
$current_country_value['title'] = ($sn_group->countries_id) ? $sn_group->country->_text->name : null;
$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название группы','model'=>$sn_group],
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'URL группы','model'=>$sn_group],
                ['name' => 'group_id','type' => Form::INPUT_TEXT,'label' => 'ID соцгруппы','model'=>$sn_group],
                ['name' => 'token','type' => Form::INPUT_TEXT,'label' => 'Токен группы','model'=>$sn_group],
                ['name' => 'social_networks_groups_main_id','type' => Form::SELECT, 'options' => SocialNetworksGroupsMain::getAllAsArray(),'label' => 'Основная группа','model'=>$sn_group, 'model_name' => 'SocialNetworksGroups'],
                ['name' => 'social_networks_id','type' => Form::SELECT, 'options' => SocialNetworks::getAllAsArray(),'label' => 'Соцсеть','model'=>$sn_group, 'model_name' => 'SocialNetworksGroups'],
                ['name' => 'countries_id','type' => Form::SEARCH_AUTOCOMPLETE,'label' => 'Страна', 'model_name' => 'SocialNetworksGroups', 'model'=>$sn_group, 'url' => 'countries/search ', 'placeholder' => 'Начните печатать название страны...', 'current_value' => $current_country_value, 'input_id' => 'socialnetworksgroups-countries_id'],
                ['name' => 'regions_id','type' => Form::SEARCH_AUTOCOMPLETE,'label' => 'Регион', 'model_name' => 'SocialNetworksGroups', 'model'=>$sn_group, 'url' => 'regions/search ', 'placeholder' => 'Начните печатать название региона...', 'current_value' => $current_region_value, 'input_id' => 'socialnetworksgroups-regions_id'],
                ['name' => 'cities_id','type' => Form::SEARCH_AUTOCOMPLETE,'label' => 'Город', 'model_name' => 'SocialNetworksGroups', 'model'=>$sn_group, 'url' => 'cities/search ', 'placeholder' => 'Начните печатать название города...', 'current_value' => $current_city_value, 'input_id' => 'socialnetworksgroups-cities_id'],
                ['name' => 'code_sm','type' => Form::INPUT_AREA_TEXT,'label' => 'Маленький блок','model'=>$sn_group, 'model_name' => 'SocialNetworksGroups', 'rows_amount' => 12],
                ['name' => 'code_md','type' => Form::INPUT_AREA_TEXT,'label' => 'Средний блок','model'=>$sn_group, 'model_name' => 'SocialNetworksGroups', 'rows_amount' => 12],
                ['name' => 'code_lg','type' => Form::INPUT_AREA_TEXT,'label' => 'Большой блок','model'=>$sn_group, 'model_name' => 'SocialNetworksGroups', 'rows_amount' => 12],
            ]
        ]
    ]
];

echo Form::widget($items);
?>
