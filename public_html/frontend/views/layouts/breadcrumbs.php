<?
use frontend\helpers\LocationHelper;

if(!empty($breadcrumbs)){
?>
    <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
    <?
    foreach($breadcrumbs as $key => $crumb){
        $city = isset($crumb['city']) ? $crumb['city']."/" : null;
    ?>
        <li
            class="breadcrmb-item
                <? if($key + 1 < count($breadcrumbs) and $key > 0){ ?>slash-content<? } ?>
                <? if($key == 0){?> slash-first <? } ?>
                <? if(!isset($breadcrumbs[$key + 1])){?> active <? } ?>"
            itemprop="itemListElement"
            itemscope itemtype="http://schema.org/ListItem">
            <? if(!isset($crumb['is_active']) OR $crumb['is_active']){?>
            <a
                href="<?= LocationHelper::getDomainForUrl($crumb['link'], $crumb['use_cookie'], $city)  ?>"
                itemprop="item"
                <? if(isset($crumb['title']) and $crumb['title']){?>
                    title="<?= $crumb['title']; ?>"
                <? } ?>
                class="<? if($key == 0){?> crumb-right-padding last-crumb<? } ?>"
            >
                <span itemprop="name"><?=  $crumb['label'] ?></span>
            </a>
            <? }elseif (isset($crumb['is_active']) and !$crumb['is_active']) {?>
                <h2 class="h2-breadcrumb "><?=  $crumb['label'] ?></h2>
            <? } ?>
            <meta itemprop="position" content="<?= $key + 1 ?>" />
        </li>
    <? } ?>
    </ol>
<? } ?>

