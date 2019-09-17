<?php
use yii\helpers\Html;

/**
 * $attributes[
 *      label,
 *      label_name - названия поля таблицы, который будет использоваться в качестве названия опшена селекта,
 *      model_name - название используемой модели,
 *      name - название параметра (должно совпадать с названием а аттрибутом в модели),
 *      options - опшены для селекта, массив в виде [[id=>, name=>], [id=>, name=>]...]
 *      selected - id выбранного элемента,
 *      null_option, boolean - нужно ли задавать нулевой опшен
 * ]
 */

$label_name = (isset($attribute['label_name']) and $attribute['label_name']) ? $attribute['label_name'] : 'name';
$null_option = (isset($attribute['null_option']) and $attribute['null_option']) ? true : false;
?>

<?= Html::beginTag('div', ['class' => 'form-group row validation-errors']);?>

<?= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);?>

    <div class="col-xs-10">
        <select name="<?= $attribute['model_name']."[".$attribute['name']."]" ?>" class="form-control">
            <? if(!empty($attribute['options'])){
                if($null_option){
                    echo "<option>Не выбрано</option>";
                }
                foreach($attribute['options'] as $option){ ?>
                <option value="<?= $option['id'] ?>" <? if($attribute['model']->{$attribute['name']} == $option['id'] OR (isset($attribute['selected']) AND $option['id'] == $attribute['selected'])){ ?>selected<? } ?>><?= $option[$label_name] ?></option>
            <? }} ?>
        </select>

    </div>

<?= Html::endTag('div');?>