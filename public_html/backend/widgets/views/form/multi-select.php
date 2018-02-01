<?php
use frontend\widgets\Selectpicker;
use yii\helpers\Html;

$baseOptions = [
    'data-width' => '100%',
];

$options = array_merge($baseOptions,$options);
?>


<div class="form-group row validation-errors">
    <?= Html::tag('label',$label,['class' => 'col-xs-2 col-form-label']);?>
    <div class="col-xs-10">
    <?= Selectpicker::widget([
        'values' => $values,
        'selected' => $selected,
        'options' => $options,
        'name' => $name

    ]);?>
    </div>
</div>
<script>
$('.selectpicker').selectpicker();
</script>