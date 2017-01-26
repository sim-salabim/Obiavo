<?php
use yii\helpers\Html;

$input = Html::activeCheckbox($attribute['model'], $attribute['name'],['label' => false, 'class' => 'input-group-addon']);

$htmlTag = Html::beginTag('div', ['class' => 'form-group row']);

    $htmlTag .= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);
    $htmlTag .= Html::beginTag('div',['class' => 'col-xs-10']);
        $htmlTag .= Html::beginTag('div',['class' => 'input-group']);
            $htmlTag .= Html::tag('span',$input,['class' => 'input-group-addon']);
            $htmlTag .= Html::tag('span','Этот пункт используется на сайте',['class' => 'form-control']);
        $htmlTag .= Html::endTag('div');
    $htmlTag .= Html::endTag('div');

$htmlTag .= Html::endTag('div');

echo $htmlTag;