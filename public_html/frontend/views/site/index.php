<?php
$this->title = 'Бесплатные объявления в россии';

$cities = common\models\City::find()->with('cityText')->all();
?>
<div class="site-index">

     <div class="cities-list">
         <?php foreach ($cities as $city) { ?>
         <a href="#"><?= $city->cityText->name?></a>
         <?php } ?>
    </div>
</div>
