<?php
/**
 * Форма для добавления и редактирования пунктов рекламы
 */

use backend\widgets\Form;


$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$advertising],
                ['name' => 'active','type' => Form::INPUT_CHECKBOX,'label' => 'Активность','model'=>$advertising, 'model_name' => 'Advertising'],
                ['name' => 'placement', 'label_name' => 'name','type' => Form::SELECT, 'options' => \common\models\Advertising::getAllPlacementForSelect(), 'model_name' => 'Advertising','label' => 'Расположение','model'=>$advertising, 'selected' => $advertising->placement, 'null_option' => false, 'id'=>'advertising_placement'],
                ['name' => 'code_ru','type' => Form::INPUT_AREA_TEXT ,'label' => 'Code RU','model_name'=>'Advertising', 'model' =>$advertising],
                ['name' => 'code_en','type' => Form::INPUT_AREA_TEXT ,'label' => 'Code EN','model_name'=>'Advertising', 'model' =>$advertising],
            ]
        ],
    ]
];

echo Form::widget($items);
?>