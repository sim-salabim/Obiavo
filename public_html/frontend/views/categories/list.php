<?php
/**
 * $categories - список Categories
 * $row_list - true or false меняет вид списка
 * $div_row_unneeded
 */
use \frontend\helpers\LocationHelper;
use yii\db\Query;

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
<? if(!isset($div_row_unneeded) or !$div_row_unneeded){?>
<div class="row margin-right-0">
<? } ?>
    <? if(!isset($row_list) OR !$row_list){ ?>

        <?php foreach ($categories as $category) { ?>
            <div class="col-lg-2 col-md-3 col-sm-4 col-6 font-14">
                <a class="cat-title hover-red" href="<?= LocationHelper::getDomainForUrl($category->url())?>"><?= $category->_text->name?></a><span class="ads-amount-city"> <?
                    if(!Yii::$app->location->city) {
                        $amnt = $category->getCounterByCountryId(Yii::$app->location->country->id)['ads_amount'] ?: 0;
                    }else{
                        $amnt = $category->getCounterByCityId(Yii::$app->location->city->id)['ads_amount'] ?: 0 ;
                    } echo $amnt;
                    ?></span>
            </div>
        <?php } ?>
    <?php }else{ ?>
            <? if($current_category->placements){?>
                <? foreach($current_category->placements as $k => $placement){ ?>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6 font-15">
                        <a href="<?= LocationHelper::getDomainForUrl($current_category->url().$placement['url']."/") ?>">
                            <?= $placement['name']?></a><span class="ads-amount-city"> <?
                            if(!Yii::$app->location->city) {
                                $amnt = $current_category->getAdsAmountByPlacementId($placement['id']);
                                $amnt = $amnt['ads_amount'] ?: 0 ;
                            }else{
                                $amnt = $current_category->getAdsAmountByPlacementId($placement['id'], Yii::$app->location->city->id);
                                $amnt = $amnt['ads_amount'] ?: 0 ;
                            } echo $amnt;
                            ?></span>
                    </div>
                <? } ?>
            <? } ?>
            <? foreach($categories as $key =>  $category){ ?>
            <div class="col-lg-2 col-md-3 col-sm-4 col-6 font-15">
                <a href="<?= LocationHelper::getDomainForUrl($category->url())?>"><?= $category->_text->name?></a><span class="ads-amount-city"> <?
                    if(!Yii::$app->location->city) {
                        $amnt = $category->getCounterByCountryId(Yii::$app->location->country->id)['ads_amount'] ?: 0;
                    }else{
                        $amnt = $category->getCounterByCityId(Yii::$app->location->city->id)['ads_amount'] ?: 0 ;
                    } echo $amnt;
                    ?></span>
            </div>
            <? } ?>
            <div class="w-100"><hr></div>
    <? } ?>
<? if(!isset($div_row_unneeded) or !$div_row_unneeded){?>
</div>
<? } ?>
