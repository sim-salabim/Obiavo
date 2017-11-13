<?php
use yii\helpers\Html;
?>

<?= Html::beginTag('div', ['class' => 'form-group row validation-errors']);?>

<?= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);?>
<div class="col-xs-10">
    <? if(!isset($attribute['manually']) or !$attribute['manually']){
        $value = ($attribute['model']->{$attribute['name']}) ? $attribute['model']->{$attribute['name']} : '';
        $name = $attribute['model_name']."[".$attribute['name']."]";
    ?>
        <?= Html::textarea($name, $value,['class' => 'form-control']);?>
    <? } else {?>
        <textarea class="form-control" name="<?= $attribute['name'] ?>" ><?= $attribute['model']->{$attribute['params_name']} ?></textarea>
    <? } ?>
</div>

<?= Html::endTag('div');?>
