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
                ['name' => 'active','type' => Form::INPUT_CHECKBOX,'label' => 'Активность','model'=>$page, 'model_name' => 'AddApplication'],
                ['name' => 'category_default','type' => Form::INPUT_CHECKBOX,'label' => 'Дефолтное сео категорий','model'=>$page, 'model_name' => 'AddApplication'],
                ['name' => 'placements_default','type' => Form::INPUT_CHECKBOX,'label' => 'Дефолтное сео для типов','model'=>$page, 'model_name' => 'AddApplication'],
            ]
        ],
        [
            'panel-title' => 'CMS данные',
            'attributes' => [
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'URL','model'=>$page->_text],
                ['name' => 'seo_title','type' => Form::INPUT_TEXT,'label' => 'Title','model'=>$page->_text],
                ['name' => 'seo_h1','type' => Form::INPUT_TEXT,'label' => 'H1','model'=>$page->_text],
                ['name' => 'seo_h2','type' => Form::INPUT_TEXT,'label' => 'H2','model'=>$page->_text],
                ['name' => 'seo_text','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'Text','model_name'=>'AddApplicationText', 'model' =>$page->_text],
                ['name' => 'seo_text1','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'Text 1','model_name'=>'AddApplicationText', 'model' =>$page->_text],
                ['name' => 'seo_text2','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'Text 2','model_name'=>'AddApplicationText', 'model' =>$page->_text],
                ['name' => 'seo_text3','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'Text 3','model_name'=>'AddApplicationText', 'model' =>$page->_text],
                ['name' => 'seo_text4','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'Text 4','model_name'=>'AddApplicationText', 'model' =>$page->_text],
                ['name' => 'seo_text5','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'Text 5','model_name'=>'AddApplicationText', 'model' =>$page->_text],
                ['name' => 'seo_text6','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'Text 6','model_name'=>'AddApplicationText', 'model' =>$page->_text],
                ['name' => 'seo_text7','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'Text 7','model_name'=>'AddApplicationText', 'model' =>$page->_text],
                ['name' => 'seo_desc','type' => Form::INPUT_AREA_TEXT,'label' => 'Description','model_name'=>'AddApplicationText', 'model' =>$page->_text],
                ['name' => 'seo_keywords','type' => Form::INPUT_TEXT,'label' => 'Keywords','model'=>$page->_text],
            ]
        ],
    ]
];

echo Form::widget($items);
?>