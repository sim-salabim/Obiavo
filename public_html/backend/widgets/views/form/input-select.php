<?php
use yii\helpers\Html;
?>

<?= Html::beginTag('div', ['class' => 'form-group row validation-errors']);?>

<?= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);?>

    <div class="col-xs-10">
        <select name="<?= $attribute['model_name']."[".$attribute['name']."]" ?>" class="form-control">
            <? if(!empty($attribute['options'])){
                foreach($attribute['options'] as $option){ ?>
                <option value="<?= $option['id'] ?>" <? if($attribute['model']->{$attribute['name']} == $option['id']){ ?>selected<? } ?>><?= $option['name'] ?></option>
            <? }} ?>
        </select>

    </div>

<?= Html::endTag('div');?>