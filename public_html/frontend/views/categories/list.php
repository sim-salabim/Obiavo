<?php
use yii\helpers\Url;
?>
<div class="row">
    <?php foreach ($categories as $category) { ?>
        <div class="col-lg-4 col-xs-2">
            <ul class="lvl-block" style="list-style: none;">
                <h4 class="lvl-1">
                    <a class="text-secondary" href="<?= Url::toRoute(['categories/index','category' => $category->url])?>"><?= $category->_text->name?></a>
                </h4>   

                <?php foreach ($category->childrens as $children) { ?>

                    <li class="lvl-2 ml-4"><a class="text-secondary" href="<?= Url::toRoute(['categories/index','category' => $children->url])?>"><?= $children->_text->name?></a></li>
                <?php } ?>
            </ul>
        </div>

    <?php } ?>
</div>