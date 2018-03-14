<?php
/**
 * Форма для создания/редактирования настроек сайта
 */

use backend\widgets\Form;

$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Параметры',
            'attributes' => [
                ['name' => 'vk_token','type' => Form::INPUT_TEXT,'label' => 'VK токен','model'=>$setting],
                ['name' => 'fb_token','type' => Form::INPUT_TEXT,'label' => 'FB токен','model'=>$setting],
            ]
        ],
    ]
];

echo Form::widget($items);
?>