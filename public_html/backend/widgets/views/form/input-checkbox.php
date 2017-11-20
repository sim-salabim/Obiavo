<?php
use yii\helpers\Html;
?>

<?= Html::beginTag('div', ['class' => 'form-group row validation-errors']);?>

<?= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);?>
<div class="col-xs-10">
    <input type="hidden" name="<?= $attribute['model_name']."[".$attribute['name']."]"?>" value="0">
    <input type="checkbox" name="<?= $attribute['model_name']."[".$attribute['name']."]" ?>" value="1" <? if($attribute['model']->{$attribute['name']}){ ?> checked <? } ?> >
</div>

<?= Html::endTag('div');?>
