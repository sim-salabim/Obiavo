<?php
use yii\helpers\Html;

/**
 * $attributes[
 *      label,
 *      model_name - название используемой модели,
 *      name - название параметра (должно совпадать с названием а аттрибутом в модели),
 *      options - опшены для селекта, массив в виде [[id=>, name=>], [id=>, name=>]...]
 *      selected - id выбранного элемента
 * ]
 */
?>

<?= Html::beginTag('div', ['class' => 'form-group row validation-errors']);?>

<?= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);?>

    <div class="col-xs-10">
        <select name="<?= $attribute['model_name']."[".$attribute['name']."]" ?>" class="form-control">
            <? if(!empty($attribute['options'])){
                foreach($attribute['options'] as $option){ ?>
                <option value="<?= $option['id'] ?>" <? if($attribute['model']->{$attribute['name']} == $option['id'] OR (isset($attribute['selected']) AND $option['id'] == $attribute['selected'])){ ?>selected<? } ?>><?= $option['name'] ?></option>
            <? }} ?>
        </select>

    </div>

<?= Html::endTag('div');?>