<?php
/**
 * Форма для добавления и редактирования языков
 * @var object $language - Объект редактируемого языка (пустой объект, если добавляем)
 * @var string toUrl ссылку куда отправлять данные на обработку
 */

use backend\widgets\Form;

$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                  ['name' => 'code','type' => Form::INPUT_TEXT,'label' => 'Домен','model'=>$language],
                  ['name' => 'active','type' => Form::INPUT_CHECKBOX_INACTIVE,'label'=>'активность','model' => $language],
                  ['name' => 'techname','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$language],
            ]
        ],
    ]
];

echo Form::widget($items);