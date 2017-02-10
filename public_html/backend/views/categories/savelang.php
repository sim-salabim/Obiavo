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

                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$category->_mttext],
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'URL','model'=>$category->_mttext],
                ['name' => 'seo_title','type' => Form::INPUT_TEXT,'label' => 'SEO заголовок','model'=>$category->_mttext],
                ['name' => 'seo_desc','type' => Form::INPUT_TEXT,'label' => 'SEO описание','model'=>$category->_mttext],
                ['name' => 'seo_keywords','type' => Form::INPUT_TEXT,'label' => 'SEO ключевые слова','model'=>$category->_mttext],
            ]
        ],
    ]
];

echo Form::widget($items);