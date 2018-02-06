<?
/**
 *  current_category, Category - текущая категория списка
 */

$location = Yii::$app->location;
$active_sns = \common\models\SocialNetworks::find()->where(['active' => 1])->orderBy(['order' => SORT_ASC])->all();
$sn_blocks = [];
foreach($active_sns as $sn){
    $sn_blocks[] = $sn->getGroupsBlock($current_category);
}
?>
<? foreach($sn_blocks as $block){?>
<div class="col-md-4">
    <? if($block){?>
        <?= $block->code_sm ?>
    <? }?>
</div>
<? } ?>