<?php
use yii\helpers\Url;
?>

<div class="row site-index">
<!--    <div class="col-12">-->
<!--        --><?//= $seo_text  ?>
<!--    </div>-->
    <div class="w-100"><hr></div>
     <div class="col-12 cities-list">
         <?php foreach ($cities as $key => $city) { ?>
         <a href="<?= Url::toRoute(["select-location/".$city->city->domain."/"])?>"><?= $city->_text->name?></a><? if(isset($cities[++$key])){?>,<? } ?>
         <?php } ?>
    </div>
    <div class="w-100"><hr></div>
    <?= $this->render('/categories/list', compact('categories'));?>

</div>