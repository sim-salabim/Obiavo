<?php
use \frontend\helpers\LocationHelper;
function sortingKids($a, $b){
    if($a->order == $b->order) {
        if ($a->brand != $b->brand) {
            return $a->brand > $b->brand;
        } else {
            return $a->techname > $b->techname;
        }
    }else{
        return $a->order > $b->order;
    }
}
?>
<div class="row margin-right-0">
    <? if(!isset($row_list) OR !$row_list){ ?>

        <?php foreach ($categories as $category) { ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <ul class="lvl-block" style="list-style: none;">
                    <li class="lvl-1">
                        <a class="text-secondary" href="/<?= LocationHelper::getDomainForUrl($category->url())?>"><?= $category->_text->name?></a>
                    </li>
                    <?

                    $children = $category->children;
                    usort($children, "sortingKids");

                    ?>
                    <?php foreach ($children as $child) { ?>
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
                        <a class="text-secondary" href="/<?= LocationHelper::getDomainForUrl($category->url())?>"><?= $category->_text->name?></a><? if(isset($categories[++$key])){?>,<? } ?>
                <? } ?>
            </span>
        </div>
    <? } ?>
</div>
