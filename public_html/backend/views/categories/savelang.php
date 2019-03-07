<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use backend\widgets\Form;

$items = [
    'saveUrl' => Url::to(''),
    'rows' => [
        [
            'panel-title' => 'Текстовые данные - {language}',
            'attributes' => [
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'URL','model'=>$category->_text],
                ['name' => 'apply_url','type' => Form::INPUT_TEXT,'label' => 'URL Подачи','model'=>$category->_text],
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название (для меню на внешке)','model'=>$category->_text],
                ['name' => 'seo_title','type' => Form::INPUT_TEXT,'label' => 'Заголовок','model'=>$category->_text],
                ['name' => 'seo_h1','type' => Form::INPUT_TEXT,'label' => 'H1','model'=>$category->_text],
                ['name' => 'seo_h2','type' => Form::INPUT_TEXT,'label' => 'H2','model'=>$category->_text],
                ['name' => 'seo_text','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'SEO текст','model_name'=>'CategoriesText', 'model' => $category->_text],
                ['name' => 'seo_desc','type' => Form::INPUT_AREA_TEXT,'label' => 'SEO описание','model_name'=>'CategoriesText', 'model' => $category->_text],
                ['name' => 'seo_keywords','type' => Form::INPUT_TEXT,'label' => 'SEO ключевые слова','model'=>$category->_text],
            ]
        ],
    ]
];

echo Form::widget($items);