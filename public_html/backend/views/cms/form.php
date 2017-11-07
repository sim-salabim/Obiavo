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
                ['name' => 'seo_title','type' => Form::INPUT_TEXT,'label' => 'Заголовок','model'=>$cms->_text],
                ['name' => 'seo_h2','type' => Form::INPUT_TEXT,'label' => 'SEO H2','model'=>$cms->_text],
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'URL','model'=>$cms->_text],
                ['name' => 'seo_keywords','type' => Form::INPUT_TEXT,'label' => 'Ключевые слова','model'=>$cms->_text],
                ['name' => 'seo_keywords','type' => Form::INPUT_AREA_TEXT,'label' => 'Ключевые слова','model_name'=>'CmsText'],
                ['name' => 'seo_text','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'Tекст','model_name'=>'CmsText', 'model' =>$cms->_text]
            ]
        ],
    ]
];

echo Form::widget($items);
?>