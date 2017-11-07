<?php
use yii\helpers\Html;
?>

<?= Html::beginTag('div', ['class' => 'form-group row validation-errors']);?>

<?= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);?>
<?
$value = (isset($attribute['value'])) ? $attribute['value'] : '';
$name = $attribute['model_name']."[".$attribute['name']."]";
?>
<div class="col-xs-10">
    <?= Html::textarea($name, $value,['class' => 'form-control']);?>
</div>

<?= Html::endTag('div');?>
