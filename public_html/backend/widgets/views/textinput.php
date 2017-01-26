<?php
use yii\helpers\Html;

 $input = Html::activeTextInput($attribute['model'], $attribute['name'],['class' => 'form-control']);

$htmlTag = Html::beginTag('div', ['class' => 'form-group row']);

        $htmlTag .= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);
        $htmlTag .= Html::tag('div',$input,['class' => 'col-xs-10']);

$htmlTag .= Html::endTag('div');

//echo $htmlTag;
?>
<?= Html::beginTag('div', ['class' => 'form-group row']);?>

<?= Html::tag('label',$attribute['label'],['class' => 'col-xs-2 col-form-label']);?>

<div class="col-xs-10">
    <ul class="nav nav-tabs custom-tab" role="tablist">
        <li role="presentation" class="active">
            <a href="#home" aria-controls="home" role="tab" data-toggle="tab">
                <span class="link-lable">RU</span>
            </a>
        </li>
       <li role="presentation">
           <a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">
               <span class="link-lable"> EN</span>
           </a>
       </li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="home">
            <input id="regiontext-name" class="form-control" name="RegionText[name]" value="Московская область" type="text">
        </div>
        <div role="tabpanel" class="tab-pane fade" id="messages">
            <input id="regiontext-name" class="form-control" name="RegionText" value="Ростовская область" type="text">
        </div>
    </div>
</div>

<?= Html::endTag('div');?>
