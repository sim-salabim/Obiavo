<?php
use yii\bootstrap\Modal;
use frontend\helpers\TextHelper;

$title = TextHelper::pageTitle("Бесплатные объявления в {city}",['city' => Yii::$app->location->name_pp]);
$this->title = $title;
?>

<div class="site-index">
    <div class="description-text">
        <?= \Yii::$app->params['domain']?> - сайт бесплатных объявлений России. Ежедневно на сайте раpмещаются тысячи частных объявлений. 34454 - Объявления.
    </div>

     <div class="cities-list">
         <?php foreach ($cities as $city) { ?>
         <a href="#"><?= $city->cityText->name?></a>
         <?= end($cities) !== $city ? ',' : ''; ?>
         <?php } ?>
    </div>

    <?= $this->render('/categories/list', compact('categories'));?>

</div>