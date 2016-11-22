<?php
$this->title = 'Главная';

$cities = common\models\City::find()->with('cityText')->all();
?>
<div class="site-index">
    Бесплатные оъявления в России
    <div class="selecter">
        выборка
    </div>
    <div class="description-text">
        <?= \Yii::$app->params['domain']?> - это ..
    </div>

     <div class="cities-list">
         <?php foreach ($cities as $city) { ?>
         <a href="#"><?= $city->cityText->name?></a>
         <?php } ?>
    </div>
</div>
