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
<div class="col-12 social-n-block"><h2><?= __('Join us in social networks') ?></h2></div>
<? foreach($sn_blocks as $block){?>
<div class="col-lg-4 col-md-6 col-sm-12" style="padding-bottom: 10px">
    <? if($block){?>
        <?= $block->code_sm ?>
    <? }?>
</div>
<? } ?>