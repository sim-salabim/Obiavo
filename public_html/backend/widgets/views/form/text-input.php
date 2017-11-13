<?php
use yii\helpers\Html;
?>

<?= Html::beginTag('div', ['class' => 'form-group row validation-errors']);?>

<?= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);?>

<div class="col-xs-10">
    <? if(!isset($attribute['manually']) or !$attribute['manually']){?>
        <?= Html::activeTextInput($attribute['model'], $attribute['name'],['class' => 'form-control']);?>
    <? } else {?>
        <input type="text" name="<?= $attribute['name'] ?>" value="<?= $attribute['model']->{$attribute['params_name']} ?>" class="form-control">
    <? } ?>
</div>

<?= Html::endTag('div');?>
