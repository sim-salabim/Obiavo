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
                ['name' => 'fb_app_id','type' => Form::INPUT_TEXT,'label' => 'FB ID приложения','model'=>$setting],
                ['name' => 'fb_email','type' => Form::INPUT_TEXT,'label' => 'Email аккаунта ФБ','model'=>$setting],
                ['name' => 'fb_app_secret','type' => Form::INPUT_TEXT,'label' => 'FB секретный ключ','model'=>$setting],
                ['name' => 'ok_public_key','type' => Form::INPUT_TEXT,'label' => 'OK публичный ключ','model'=>$setting],
                ['name' => 'ok_secret_key','type' => Form::INPUT_TEXT,'label' => 'OK секретный ключ','model'=>$setting],
            ]
        ],
    ]
];

echo Form::widget($items);
?>