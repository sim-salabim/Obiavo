<?php
use yii\helpers\Html;
?>

<?= Html::beginTag('div', ['class' => 'form-group row validation-errors']);?>

<?= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);?>

<div class="col-xs-10">
    <?= Html::textarea($attribute['model'], $attribute['value'],['class' => 'form-control']);?>
</div>

<?= Html::endTag('div');?>
