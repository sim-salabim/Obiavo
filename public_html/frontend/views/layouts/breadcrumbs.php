<?
use frontend\helpers\LocationHelper;

if(!empty($breadcrumbs)){
?>
    <ol class="breadcrumb">
    <?
    foreach($breadcrumbs as $key => $crumb){
    ?>
        <li class="breadcrumb-item <? if(!isset($breadcrumbs[$key + 1])){?> active <? } ?>">
            <a href="<?= LocationHelper::getDomainForUrl($crumb['link'])  ?>"><?=  $crumb['label'] ?></a>
        </li>
    <? } ?>
    </ol>
<? } ?>

