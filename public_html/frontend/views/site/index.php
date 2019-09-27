<?php
use yii\helpers\Url;
?>

<div class="row site-index">
    <? if($show_cities_list){?>
        <div class="w-100"><hr></div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-6 font-15">
                <a href="<?= Url::toRoute(["/"])?>"><?= Yii::$app->location->country->_text->name?></a> <span class="ads-amount-city"> <?= $country_amount ?></span>
            </div>
             <?php foreach ($cities as $key => $city) { ?>
                 <div class="col-lg-3 col-md-4 col-sm-6 col-6 font-15">
                     <a href="<?= Url::toRoute(["/".$city->city->domain."/"])?>"><?= $city->_text->name?></a> <span class="ads-amount-city"> <?= $city->city->ads_amount ?: 0 ?></span>
                 </div>
             <?php } ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-6 font-15">
                <a href="<? if(\frontend\components\Location::getDefaultLanguageId() == \common\models\Language::LANG_RU){
                            echo Url::toRoute(["/vybor-goroda/"]);
                        }elseif(\frontend\components\Location::getDefaultLanguageId() == \common\models\Language::LANG_EN){
                            echo Url::toRoute(["/choose-city/"]);
                        }
?>"><?= __('_City') ?></a>
            </div>
        <div class="w-100"><hr></div>
    <? }else{ ?>
        <div class="w-100"><hr></div>
    <? } ?>
    <? $div_row_unneeded = true; ?>
    <?= $this->render('/categories/list', compact('categories', 'div_row_unneeded'));?>
</div>
<?
$head_cms_page = \common\models\Cms::getByTechname('site-header');
if($head_cms_page and $head_cms_page->_text->seo_text2){ ?>
<div class="row">
    <div class="w-100">
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <?
            $text = str_replace(['{key:location}', '{key:location-in}', '{key:location-of}'], [$place->_text->name, __('in')." ".$place->_text->name_rp, $place->_text->name_pp], $head_cms_page->_text->seo_text2);
            echo $text;
        ?>
    </div>
</div>
<div class="row">
    <div class="w-100">
        <hr>
    </div>
</div>
<? }?>
<?=  $this->render('/partials/_ads_list.php',
    [
        'padding_top_20' => true,
        'ads_search' => $ads_search,
        'library_search'=> $library_search,
        'title' => countString($ads_search['count'], [__('free ad'), __('free ads_r_p'), __('free ads_im_p') ])." ".__('in')." ".$place->_text->name_rp,
        'no_ads_title' => __('No ads found'),
        'show_sn_widgets' => true,
        'root_url' => $root_url,
        'page_pagination_title' => $page_pagination_title,
//            'current_category' => $current_category,
//            'current_action' => $current_action
    ]) ?>
