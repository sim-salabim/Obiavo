<?
use yii\helpers\Url;

if(!empty($breadcrumbs)){
?>
    <ol class="breadcrumb">
    <?
    foreach($breadcrumbs as $key => $crumb){
    ?>
        <li class="breadcrumb-item <? if(!isset($breadcrumbs[$key + 1])){?> active <? } ?>">
            <? if(!isset($breadcrumbs[$key + 1])){
               echo $crumb['label'];
            }else{ ?>
                <a href="<?= URL::to($crumb['link']) ?>"><?=  $crumb['label'] ?></a>
            <? } ?>
        </li>
    <? } ?>
    </ol>
<? } ?>

