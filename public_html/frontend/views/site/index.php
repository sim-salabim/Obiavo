<?php
use yii\helpers\Url;
?>

<div class="row site-index">
    <div class="w-100"><hr></div>
     <div class="col-12 cities-list">
         <? if(Yii::$app->location->country){?>
             <a href="<?= Url::toRoute(["/"])?>"><?= Yii::$app->location->country->_text->name?></a><? if(isset($cities[0])){?>,<? } ?>
         <? } ?>
         <?php foreach ($cities as $key => $city) { ?>
         <a href="<?= Url::toRoute(["/".$city->city->domain."/"])?>"><?= $city->_text->name?></a>,
         <?php } ?>
         <a href="<?= yii\helpers\Url::toRoute('/vybor-goroda') ?>" rel="nofollow"><?= __('Other city') ?></a>
    </div>
    <div class="w-100"><hr></div>
    <?= $this->render('/categories/list', compact('categories'));?>

</div>