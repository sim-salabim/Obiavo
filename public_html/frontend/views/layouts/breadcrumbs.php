<?
use frontend\helpers\LocationHelper;

if(!empty($breadcrumbs)){
?>
    <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
    <?
    foreach($breadcrumbs as $key => $crumb){
    ?>
        <li class="breadcrumb-item <? if(!isset($breadcrumbs[$key + 1])){?> active <? } ?>" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a href="<?= LocationHelper::getDomainForUrl($crumb['link'])  ?>" itemscope itemtype="http://schema.org/Thing" itemprop="item"><span itemprop="name"><?=  $crumb['label'] ?></span></a>
            <meta itemprop="position" content="<?= $key + 1 ?>" />
        </li>
    <? } ?>
    </ol>
<? } ?>

