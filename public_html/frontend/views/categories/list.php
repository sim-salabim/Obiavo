<?php
use \frontend\helpers\LocationHelper;
?>
<div class="row">
    <? if(!isset($row_list) OR !$row_list){ ?>

        <?php foreach ($categories as $category) { ?>
            <div class="col-lg-4 col-xs-2">
                <ul class="lvl-block" style="list-style: none;">
                    <h4 class="lvl-1">
                        <a class="text-secondary" href="/<?= LocationHelper::getDomainForUrl($category->url())?>"><?= $category->_text->name?></a>
                    </h4>

                    <?php foreach ($category->children as $child) { ?>
                        <li class="lvl-2 ml-4" style="color: #777;"><a href="/<?= LocationHelper::getDomainForUrl($child->url())?>"><?= $child->_text->name?></a></li>
                    <?php } ?>
                </ul>
            </div>

        <?php } ?>
    <?php }else{ ?>
        <div class="col-12 padding-bottom-10">
            <span class="color-grey">
                <? if($current_category->placements){?>
                    <? foreach($current_category->placements as $placement){ ?>
                        <a class="text-secondary"
                           href="/<?= LocationHelper::getDomainForUrl($current_category->url().$placement->url()) ?>">
                            <?= $placement->_text->name?></a>,
                    <? } ?>
                <? } ?>
                <? foreach($categories as $key =>  $category){ ?>
                    <a class="text-secondary" href="/<?= LocationHelper::getDomainForUrl($category->url())?>"><?= $category->_text->name?></a><? if(isset($categories[++$key])){?>,<? } ?>
                <? } ?>
            </span>
        </div>
    <? } ?>
</div>
