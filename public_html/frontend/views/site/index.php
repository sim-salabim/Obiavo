<?php
use yii\helpers\Url;
?>

<div class="row site-index">
    <div class="col-12">
        <? $header_str = str_replace(['{site-url}','{count-obiavlenya}'], [\Yii::$app->location->domain, countString(\common\models\Ads::countAds(), [__('one_ad'), __('two_ads'), __('more_ads')])],\common\models\Cms::getByTechname('site-header')->_text->seo_text)?>
        <?= $header_str  ?>
    </div>
    <div class="w-100"><hr></div>
     <div class="col-12 cities-list">
         <?php foreach ($cities as $key => $city) { ?>
         <a href="<?= Url::toRoute(["select-location/".$city->city->domain."/"])?>"><?= $city->_text->name?></a><? if(isset($cities[++$key])){?>,<? } ?>
         <?php } ?>
    </div>
    <div class="w-100"><hr></div>
    <?= $this->render('/categories/list', compact('categories'));?>

</div>