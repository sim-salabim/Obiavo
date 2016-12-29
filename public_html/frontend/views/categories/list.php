<?php
use yii\helpers\Url;
?>
<div class="categories-list">
    <?php foreach ($categories as $category) { ?>
        <ul class="lvl-block">
            <li class="lvl-1">
                <a href="<?= Url::toRoute(['categories/index','category' => $category->url])?>"><?= $category->_text->name?></a>
            </li>

            <?php foreach ($category->childrens as $children) { ?>
            <li class="lvl-2"><a href="<?= Url::toRoute(['categories/index','category' => $children->url])?>"><?= $children->_text->name?></a></li>
            <?php } ?>

        </ul>
     <?php } ?>
</div>