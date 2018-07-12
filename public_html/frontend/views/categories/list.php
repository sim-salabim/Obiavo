<?php
use \frontend\helpers\LocationHelper;
?>
<div class="row">
    <? if(!isset($row_list) OR !$row_list){ ?>

        <?php foreach ($categories as $category) { ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <ul class="lvl-block" style="list-style: none;">
                    <li class="lvl-1">
                        <a class="text-secondary" href="/<?= LocationHelper::getDomainForUrl($category->url())?>"><?= $category->_text->name?></a>
                    </li>

                    <?php foreach ($category->children as $child) { ?>
                        <? if($child->active){ ?>
                        <li class="lvl-2 ml-4" style="color: #777;"><a href="/<?= LocationHelper::getDomainForUrl($child->url())?>"><?= $child->_text->name?></a></li>
                    <? } ?>
                    <?php } ?>
                </ul>
            </div>

        <?php } ?>
    <?php }else{ ?>
        <div class="col-12 ">
            <span class="color-grey">
                <? if($current_category->placements){?>
                    <? foreach($current_category->placements as $k => $placement){ ?>
                        <a class="text-secondary"
                           href="/<?= LocationHelper::getDomainForUrl($current_category->url().$placement['url']."/") ?>">
                            <?= $placement['name']?></a><? if(($k + 1) < count($current_category->placements) or count($categories)){?>,<? } ?>
                    <? } ?>
                <? } ?>
                <? foreach($categories as $key =>  $category){ ?>
                    <? if($category->active){ ?>
                        <a class="text-secondary" href="/<?= LocationHelper::getDomainForUrl($category->url())?>"><?= $category->_text->name?></a><? if(isset($categories[++$key])){?>,<? } ?>
                    <? }?>
                <? } ?>
            </span>
        </div>
    <? } ?>
</div>
