<?php
/**
 * Форма для добавления и редактирования пунктов категорий
 * @var object $category - Объект редактируемой категории (пустой объект, если добавляем)
 * @var object $categoryGenerate - Объект из связанной модели сгерерированных категорий
 * @var string toUrl ссылку куда отправлять данные на обработку
 */

use yii\helpers\Url;
use yii\bootstrap\Html;
use backend\widgets\Form;
use yii\helpers\ArrayHelper;

$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                ['name' => 'techname','type' => Form::INPUT_TEXT,'label' => 'Техническое название (уникальное, латиница)','model'=>$cms],
            ]
        ],
        [
            'panel-title' => 'CMS данные',
            'attributes' => [
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название (h1)','model'=>$cms->_text],
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'URL','model'=>$cms->_text],
                ['name' => 'text','type' => Form::INPUT_TEXT,'label' => 'Текст','model'=>$cms->_text],
            ]
        ],
    ]
];

echo Form::widget($items);
?>