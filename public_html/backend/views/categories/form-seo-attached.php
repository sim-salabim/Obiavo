<?php
/**
 * Форма для добавления и редактирования связанных действий (placements)
 * @var array[object] categoryPlacementsTexts[] - Массив обьектов редактируемых типов
 * @var string toUrl ссылку куда отправлять данные на обработку
 * @var int id, id категории
 * @var array[object] categories_placements, categories_has_placements
 */

use backend\widgets\Form;
use \common\models\Language;
use \common\models\CategoryPlacementText;
use \common\models\CategoryPlacement;
use \common\models\PlacementsText;

$categories_placements = CategoryPlacement::find()->where(['categories_id'=>$id])->all();
if($categories_placements){
    $arr = [];
    foreach($categories_placements as $cPl){
        $categoryPlacementText = CategoryPlacementText::find()->where(['category_placement_id' => $cPl->id])->one();
        if(!$categoryPlacementText){
            $categoryPlacementText = new CategoryPlacementText();
            $categoryPlacementText->category_placement_id = $cPl->id;
        }
        $arr[$cPl->id] = $categoryPlacementText;
    }
}
$items = [
    'saveUrl' => $toUrl,
    'rows' => []
];
if(!empty($arr)) {

    foreach ($arr as $key => $text_model) {
        $category_placement = CategoryPlacement::findOne(['id' => $key]);
        $placement_name = PlacementsText::findOne(['placements_id' => $category_placement->placements_id, 'languages_id' => Language::getDefault()->id])->name;
        $placement_form_arr = ['panel-title' => 'Seo для связанных типов "'.$placement_name.'"' , 'attributes' => []];

        $placement_form_arr['attributes'][] = ['name' => "name[{$key}]", 'type' => Form::INPUT_TEXT, 'label' => 'Name', 'model' => $text_model, 'manually' => true, 'params_name' => 'name'];
        $placement_form_arr['attributes'][] = ['name' => "seo_title[{$key}]", 'type' => Form::INPUT_TEXT, 'label' => 'Title', 'model' => $text_model, 'manually' => true, 'params_name' => 'seo_title'];
        $placement_form_arr['attributes'][] = ['name' => "seo_h1[{$key}]", 'type' => Form::INPUT_TEXT, 'label' => 'H1', 'model' => $text_model, 'manually' => true, 'params_name' => 'seo_h1'];
        $placement_form_arr['attributes'][] = ['name' => "seo_h2[{$key}]", 'type' => Form::INPUT_TEXT, 'label' => 'H2', 'model' => $text_model, 'manually' => true, 'params_name' => 'seo_h2'];
        $placement_form_arr['attributes'][] = ['name' => "seo_text[{$key}]", 'type' => Form::INPUT_TEXT_AREA_REACH, 'label' => 'Text', 'model_name' => 'CategoryPlacementText', 'model' => $text_model, 'manually' => true, 'params_name' => 'seo_text'];
        $placement_form_arr['attributes'][] = ['name' => "seo_desc[{$key}]", 'type' => Form::INPUT_AREA_TEXT, 'label' => 'Description', 'model_name' => 'CategoryPlacementText', 'model' => $text_model, 'manually' => true, 'params_name' => 'seo_desc'];
        $placement_form_arr['attributes'][] = ['name' => "seo_keywords[{$key}]", 'type' => Form::INPUT_TEXT, 'label' => 'Keywords', 'model' => $text_model, 'manually' => true, 'params_name' => 'seo_keywords'];
        array_push($items['rows'], $placement_form_arr);
    }
}
echo Form::widget($items);
?>