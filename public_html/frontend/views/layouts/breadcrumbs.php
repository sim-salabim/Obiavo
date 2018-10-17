<?
use frontend\helpers\LocationHelper;

if(!empty($breadcrumbs)){
?>
    <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
    <?
    foreach($breadcrumbs as $key => $crumb){
    ?>
        <li class="breadcrmb-item <? if($key + 1 < count($breadcrumbs) and $key > 0){ ?>slash-content<? } ?> <? if($key == 0){?> slash-first <? } ?><? if(!isset($breadcrumbs[$key + 1])){?> active <? } ?>" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a href="<?= LocationHelper::getDomainForUrl($crumb['link'], $crumb['use_cookie'])  ?>" itemscope itemtype="http://schema.org/Thing" itemprop="item" class="<? if($key == 0){?> crumb-right-padding <? } ?>"><span itemprop="name"><?=  $crumb['label'] ?></span></a>
            <meta itemprop="position" content="<?= $key + 1 ?>" />
        </li>
    <? } ?>
    </ol>
<? } ?>

