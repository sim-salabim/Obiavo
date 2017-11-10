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
$test = ArrayHelper::getColumn($category->placements,'id');
if($category->placements){
    $arr = [];
    foreach($category->placements as $pl){
        $category_placement = CategoryPlacement::find()->withText(['languages_id' => Language::getDefault()->id])->where(['categories_id' => $category->id, 'placements_id' => $pl->id])->one();
        $arr[$category_placement->id] = [$category_placement];
    }
}
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
                ['name' => 'seo_desc','type' => Form::INPUT_AREA_TEXT,'label' => 'SEO описание','model_name'=>'CategoriesText', 'model' => $category->_text],
                ['name' => 'seo_h2','type' => Form::INPUT_TEXT,'label' => 'SEO H2','model'=>$category->_text],
                ['name' => 'seo_text','type' => Form::INPUT_TEXT_AREA_REACH ,'label' => 'SEO текст','model_name'=>'CategoriesText', 'model' => $category->_text]
            ]
        ]
    ]
];
if(!empty($arr)) {

    foreach ($arr as $key => $text_model) {
        $category_placement = CategoryPlacement::findOne(['id' => $key]);
        $placement_name = PlacementsText::findOne(['placements_id' => $category_placement->placements_id, 'languages_id' => Language::getDefault()->id])->name;
        $placement_form_arr = ['panel-title' => 'Seo для связанных типов "'.$placement_name.'"' , 'attributes' => []];
        $placement_form_arr['attributes'][] = ['name' => 'seo_title', 'type' => Form::INPUT_TEXT, 'label' => 'Сео заголовок', 'model' => $text_model[0]->_text];
        $placement_form_arr['attributes'][] = ['name' => 'seo_h2', 'type' => Form::INPUT_TEXT, 'label' => 'Сео H2', 'model' => $text_model[0]->_text];
        $placement_form_arr['attributes'][] = ['name' => 'seo_name', 'type' => Form::INPUT_TEXT, 'label' => 'Сео name', 'model' => $text_model[0]->_text];
        $placement_form_arr['attributes'][] = ['name' => 'seo_text', 'type' => Form::INPUT_TEXT_AREA_REACH, 'label' => 'Сео текст', 'model_name' => 'CategoryPlacementText', 'model' => $text_model[0]->_text];
        $placement_form_arr['attributes'][] = ['name' => 'seo_desc', 'type' => Form::INPUT_AREA_TEXT, 'label' => 'Сео описание', 'model_name' => 'CategoryPlacementText', 'model' => $text_model[0]->_text];
        $placement_form_arr['attributes'][] = ['name' => 'seo_keywords', 'type' => Form::INPUT_TEXT, 'label' => 'Keywords', 'model' => $text_model[0]->_text];
        array_push($items['rows'], $placement_form_arr);
    }
}
echo Form::widget($items);
?>