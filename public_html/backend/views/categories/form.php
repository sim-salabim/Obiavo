<?php
/**
 * Форма для добавления и редактирования пунктов категорий
 * @var object $category - Объект редактируемой категории (пустой объект, если добавляем)
 * @var object $categoryGenerate - Объект из связанной модели сгерерированных категорий
 * @var string toUrl ссылку куда отправлять данные на обработку
 */

use backend\widgets\Form;
use yii\helpers\ArrayHelper;
use \common\models\Language;
use common\models\Placement;
use \common\models\CategoryPlacement;
use \common\models\PlacementsText;

$placements = Placement::find()->withText(['languages_id' => Language::getDefault()->id])->all();
$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                ['name' => 'techname','type' => Form::INPUT_TEXT,'label' => 'Название (techname)','model'=>$category],
                ['name' => 'active','type' => Form::INPUT_CHECKBOX,'label' => 'Активность','model'=>$category, 'model_name' => 'Category'],
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
                ['name' => 'url','type' => Form::INPUT_TEXT,'label' => 'URL','model'=>$category->_text],
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Name','model'=>$category->_text],
                ['name' => 'seo_title','type' => Form::INPUT_TEXT,'label' => 'Title','model'=>$category->_text],
                ['name' => 'seo_h1','type' => Form::INPUT_TEXT,'label' => 'H1','model'=>$category->_text],
                ['name' => 'seo_h2','type' => Form::INPUT_TEXT,'label' => 'H2','model'=>$category->_text],
                ['name' => 'seo_text','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'Text','model_name'=>'CategoriesText', 'model' => $category->_text],
                ['name' => 'seo_desc','type' => Form::INPUT_AREA_TEXT,'label' => 'Description','model_name'=>'CategoriesText', 'model' => $category->_text],
                ['name' => 'seo_keywords','type' => Form::INPUT_TEXT,'label' => 'Keywords','model'=>$category->_text],
            ]
        ]
    ]
];

echo Form::widget($items);
?>