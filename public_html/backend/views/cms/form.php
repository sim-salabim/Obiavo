<?php
/**
 * Форма для добавления и редактирования пунктов категорий
 * @var object $category - Объект редактируемой категории (пустой объект, если добавляем)
 * @var object $categoryGenerate - Объект из связанной модели сгерерированных категорий
 * @var string toUrl ссылку куда отправлять данные на обработку
 */

use backend\widgets\Form;

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
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'URL','model'=>$cms->_text],
                ['name' => 'seo_title','type' => Form::INPUT_TEXT,'label' => 'Title','model'=>$cms->_text],
                ['name' => 'seo_h1','type' => Form::INPUT_TEXT,'label' => 'H1','model'=>$cms->_text],
                ['name' => 'seo_h2','type' => Form::INPUT_TEXT,'label' => 'H2','model'=>$cms->_text],
                ['name' => 'seo_text','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'Text','model_name'=>'CmsText', 'model' =>$cms->_text],
                ['name' => 'seo_desc','type' => Form::INPUT_AREA_TEXT,'label' => 'Description','model_name'=>'CmsText', 'model' =>$cms->_text],
                ['name' => 'seo_keywords','type' => Form::INPUT_TEXT,'label' => 'Keywords','model'=>$cms->_text],
            ]
        ],
    ]
];

echo Form::widget($items);
?>