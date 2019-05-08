<?php
use yii\helpers\Url;
?>

<div class="row site-index">
    <div class="w-100"><hr></div>
         <?php foreach ($cities as $key => $city) { ?>
             <div class="col-lg-2 col-md-3 col-sm-4 col-6 font-12">
                 <a href="<?= Url::toRoute(["/".$city->city->domain."/"])?>"><?= $city->_text->name?></a> <span class="ads-amount-city">(<?= $city->city->ads_amount ?: 0 ?>)</span>
             </div>
         <?php } ?>
    <div class="w-100"><hr></div>
    <?= $this->render('/categories/list', compact('categories'));?>

</div>