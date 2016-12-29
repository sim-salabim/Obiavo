<?php
use yii\bootstrap\Modal;

$this->title = "Бесплатные объявления в " . Yii::$app->location->name_pp;

$cities = common\models\City::find()->withText()->all();
?>

<div class="site-index">
    
     <div class="cities-list">
         <?php foreach ($cities as $city) { ?>
         <a href="#"><?= $city->cityText->name?></a>
         <?php } ?>
    </div>
    
    <?= $this->render('/categories/list', compact('categories'));?>   

</div>