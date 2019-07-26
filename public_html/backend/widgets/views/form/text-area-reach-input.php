<?
use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
?>
<?= Html::beginTag('div', ['class' => 'form-group row validation-errors']);?>

<?= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);?>
<div class="col-xs-10">
    <? if(!isset($attribute['manually']) or !$attribute['manually']){?>
        <?= CKEditor::widget([
            'editorOptions' => [
                'preset' => 'full',
                'inline' => false, //по умолчанию false
            ],
            'name' => $attribute['model_name']."[".$attribute['name']."]",
            'value' => $attribute['model']->{$attribute['name']}
        ]);
        ?>
    <? } else {?>
        <?= CKEditor::widget([
            'editorOptions' => [
                'preset' => 'full',
                'allowedContent' => true,
                'inline' => false, //по умолчанию false
            ],
            'name' => $attribute['name'],
            'value' => $attribute['model']->{$attribute['params_name']}
        ]);
        ?>
    <? } ?>
</div>
<?= Html::endTag('div');?>

