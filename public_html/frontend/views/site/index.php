<?php
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = "Бесплатные объявления в " . Yii::$app->location->name_pp;

$cities = common\models\City::find()->withText()->all();
?>

<div id="loadcontent-container" style="display: none"></div>

<div class="site-index">
    <div id="rooter"></div>
     <div class="cities-list">
         <?php foreach ($cities as $city) { ?>
         <a href="#"><?= $city->cityText->name?></a>
         <?php } ?>
    </div>

    <div class="categories-list">
         <?php foreach ($categories as $category) { ?>
            <ul class="lvl-block">
                <li class="lvl-1">
                    <a href="<?= Url::toRoute("/{$category->url}")?>"><?= $category->_text->name?></a>
                </li>

                <?php foreach ($category->childrens as $children) { ?>
                <li class="lvl-2"><a href="<?= Url::toRoute("/{$children->url}")?>"><?= $children->_text->name?></a></li>
                <?php } ?>

            </ul>
         <?php } ?>
    </div>

</div>

<style>
    .categories-list {
        display: flex;
        flex-wrap: wrap;
        margin-top: 50px;
    }

    .categories-list > .lvl-block {
        width: 30%;
        list-style: none;
        margin-bottom: 50px;
    }

    .lvl-block > .lvl-1 {
        font-size: 20px;
        margin-bottom: 10px;
        color: blue;
    }

    .lvl-2 a {
        font-size: 14px;
        line-height: 17px;
        display: block;
        color: #333;
    }
</style>
