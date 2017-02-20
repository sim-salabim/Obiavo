<?php
use yii\bootstrap\Modal;
use frontend\helpers\TextHelper;
?>

<div class="site-index">
    <div class="description-text">
        <?= \Yii::$app->location->domain?> - сайт бесплатных объявлений России. Ежедневно на сайте раpмещаются тысячи частных объявлений. 34454 - Объявления.
    </div>

     <div class="cities-list">
         <?php foreach ($cities as $city) { ?>
         <a href="#"><?= $city->_text->name?></a>
         <?= end($cities) !== $city ? ',' : ''; ?>
         <?php } ?>
    </div>

    <?= $this->render('/categories/list', compact('categories'));?>

</div>