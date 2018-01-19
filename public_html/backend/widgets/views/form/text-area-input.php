<?php
use yii\helpers\Html;

?>

<?= Html::beginTag('div', ['class' => 'form-group row validation-errors']);?>
<? $rows_amount = isset($attribute['rows_amount']) ? $attribute['rows_amount'] : 3?>
<?= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);?>
<div class="col-xs-10">
    <? if(!isset($attribute['manually']) or !$attribute['manually']){
        $value = ($attribute['model']->{$attribute['name']}) ? $attribute['model']->{$attribute['name']} : '';
        $name = $attribute['model_name']."[".$attribute['name']."]";
    ?>
        <?= Html::textarea($name, $value,['class' => 'form-control', 'id' => mb_strtolower($attribute['model_name'])."-".$attribute['name'], 'rows' => $rows_amount]);?>
    <? } else {?>
        <textarea class="form-control" rows="<?= $rows_amount ?>" id="<?= mb_strtolower($attribute['model_name'])."-".$attribute['name'] ?>" name="<?= $attribute['name'] ?>" ><?= $attribute['model']->{$attribute['params_name']} ?></textarea>
    <? } ?>
</div>

<?= Html::endTag('div');?>
