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

$placements = common\models\Placement::find()->withText()->all();

$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                ['name' => 'techname','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$category],
                [
                    'name' => 'placements',
                    'type' => Form::MULTISELECT,
                    'label'=>'Типы размещения объявлений',
                    'model' => $category,

                    'selectpicker' => [
                      'values' => ArrayHelper::map($placements, 'id','_text.name'),
                      'selected' => ArrayHelper::getColumn($category->placements,'id'),
                      'options' => ['multiple' => true]
                    ]
                ],
            ]
        ],
        [
            'panel-title' => 'Сео данные',
            'attributes' => [
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$category->_text],
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'URL','model'=>$category->_text],
                ['name' => 'seo_title','type' => Form::INPUT_TEXT,'label' => 'SEO заголовок','model'=>$category->_text],
                ['name' => 'seo_name','type' => Form::INPUT_TEXT,'label' => 'SEO название (для меню на внешке)','model'=>$category->_text],
                ['name' => 'seo_keywords','type' => Form::INPUT_TEXT,'label' => 'SEO ключевые слова','model'=>$category->_text],
                ['name' => 'seo_desc','type' => Form::INPUT_AREA_TEXT,'label' => 'SEO описание','model_name'=>'CategoriesText'],
                ['name' => 'seo_h2','type' => Form::INPUT_TEXT,'label' => 'SEO H2','model'=>$category->_text],
                ['name' => 'seo_text','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'SEO текст','model_name'=>'CategoriesText', 'model' => $category->_text]
            ]
        ],
    ]
];

echo Form::widget($items);
?>