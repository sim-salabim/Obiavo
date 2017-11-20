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
                ['name' => 'seo_title','type' => Form::INPUT_TEXT,'label' => 'Заголовок','model'=>$cms->_text],
                ['name' => 'seo_h2','type' => Form::INPUT_TEXT,'label' => 'SEO H2','model'=>$cms->_text],
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'URL','model'=>$cms->_text],
                ['name' => 'seo_keywords','type' => Form::INPUT_TEXT,'label' => 'Ключевые слова','model'=>$cms->_text],
                ['name' => 'seo_desc','type' => Form::INPUT_AREA_TEXT,'label' => 'Описание','model_name'=>'CmsText', 'model' =>$cms->_text],
                ['name' => 'seo_text','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'Tекст','model_name'=>'CmsText', 'model' =>$cms->_text]
            ]
        ],
    ]
];

echo Form::widget($items);