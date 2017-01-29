<?php
use yii\helpers\Html;
?>

<?= Html::beginTag('div', ['class' => 'form-group row']);?>

<?= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);?>

<div class="col-xs-10">
    <?= Html::activeTextInput($attribute['model'], $attribute['name'],['class' => 'form-control']);?>
</div>

<?= Html::endTag('div');?>
