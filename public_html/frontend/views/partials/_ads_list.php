<?
/**
 * title, str
 * library_search, LibrarySearch - настртоенный обьект LibrarySearch
 * no_ads_title, str - текс показываемый при отсутствии обьявлений в списке
 * current_category, Category
 * show_sn_widgets, boolean - показывать или нет блок виджетов соцсетей
 * root_url, string|null - роут без GET параметров
 */
$current_category = isset($current_category) ? $current_category : null;
$current_action = isset($current_action) ? $current_action : null;
$show_sn_widgets = isset($show_sn_widgets) ? $show_sn_widgets : true;
$root_url = isset($root_url) ? $root_url : null;
?>
<!--<hr class="extra-margin-bottom45">-->
<div class="row padding-top-7">
    <div class="col-lg-9 col-md-6 col-sm-12 text-align-left">
        <? $title ?>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 d-flex justify-content-end ">
        <?=  $this->render('/partials/_filter_select.php',
            []) ?>
    </div>
<!--    <div class="col-lg-2 col-md-3 col-sm-12 text-align-right-grid">-->
<!--        --><?//= $this->render('/partials/_grid_settings.php', []); ?>
<!--    </div>-->
</div>
<hr class="margin-top-1045">
<div class="row">
    <? if($ads_search['count'] == 0){?>
        <div class="col-12">
            <?= $no_ads_title; ?>
        </div>
    <? }else{?>
        <? foreach($ads_search['items'] as $ad){?>
            <div class="col-lg-2 col-md-3 col-4 nonpadding-right image-div">
                <? $avatar = $ad->avatar(true); ?>
                <img class="img-fluid" src="<?= $avatar ?>" alt="<? if(strpos($avatar, 'placeholder') !== false){ echo __('No photo'); }else{ echo __('photo')." ".$ad->title; }?>">
            </div>
            <div class="col-lg-10 col-md-9 col-8 nonpadding-left-items">
                <span><strong><a href="/<?= $ad->url() ?>" ><?= $ad->title ?></a></strong></span>
                <p class="price-p"><strong><?= $ad->price . " " . \common\models\Ads::PRICE_LABEL ?></strong></p>
                <span><small class="ads-pre-text"><?= cutText($ad->text, 50) ?></small></span><br/>
                <div class="line-height-block">
                    <span><small class="ads-pre-text"><?= $ad->placement->_text->name ?>, <?= $ad->category->_text->name ?>, <?= $ad->city->_text->name ?></small></span><br/>
                    <span><small class="ads-pre-text"><?= $ad->getHumanDate() ?></small></span><br/>
                    <?
                    $user = Yii::$app->user->identity;
                    ?>
                    <? if ($ad->created_at < $ad->expiry_date) { ?>
                        <span>
                            <? if(time() < $ad->expiry_date and $ad->active and ($user and $user->id == $ad->user->id)){ ?>
                                <small id="small<?= $ad->id ?>" class="date_string">
                                    <?= __("Active to") . " " . $ad->getHumanDate(\common\models\Ads::DATE_TYPE_EXPIRATION) ?>
                                </small>
                            <? } ?>
                            <? if(($user and $user->id == $ad->user->id) or ($user and $user->is_admin) or (isset($_COOKIE['session_token']) and $_COOKIE['session_token'] == $ad->session_token)) { ?>
                                <? if(!$ad->active OR time() > $ad->expiry_date  and ($user and $user->id == $ad->user->id)){ ?>
                                    <small class="date_string">
                                        <a id="repost<?= $ad->id ?>" onclick="repostAd(<?= $ad->id ?>)"><?= __('Repost the ad') ?></a>
                                    </small>
                                <? } ?>
                                <small class="date_string">
                                    <? if((time() - $ad->updated_at) > 2592000 and $ad->active and time() < $ad->expiry_date and ($user and $user->id == $ad->user->id)){ ?>
                                        <a id="raise<?=$ad->id ?>" onclick="raiseAd(<?= $ad->id ?>)"><?= __('Raise') ?></a>
                                    <? } ?>
                                    <? if($ad->active AND time() < $ad->expiry_date and ($user and $user->id == $ad->user->id)){ ?>
                                        <a id="active<?= $ad->id?>" onclick="inactivateAd(<?= $ad->id ?>)"><?= __('Inactivate ad') ?></a>
                                    <? }?>
                                    <? if(($user and $user->is_admin) or ($user and $ad->users_id == $user->id) or (isset($_COOKIE['session_token']) and $_COOKIE['session_token'] == $ad->session_token)){?>
                                        <a id="edit<?= $ad->id?>" onclick="moveToEdit('<?= $ad->url ?>')"><?= __('Edit') ?></a>
                                    <? } ?>
                                </small>
                            <? }?>
                        <br/>
                    <? } else { ?>
                        <span>
                            <small class="date_string">
                                <?= __("Inactive since") . " " . $ad->getHumanDate(\common\models\Ads::DATE_TYPE_EXPIRATION) ?>
                                <a><?= __('Repost') ?></a>
                            </small>
                        </span>
                        <br/>
                    <? } ?>
                </div>
            </div>
            <div class="col-12 placeholder"></div>
        <? } ?>
        <? if($library_search->limit < $ads_search['count']){?>
            <?= $this->render('/partials/_pagination.php',
                [
                    'ads_search' => $ads_search,
                    'library_search'=> $library_search,
                    'current_category' => $current_category,
                    'current_action' => $current_action,
                    'root_url'       => $root_url
                ])?>
        <? }?>
    <? } ?>
    <? if($library_search->limit > $ads_search['count']){ ?>
        <div class="col-lg-12"><hr class="end-list-padding"></div>
    <? } ?>
    <? if($show_sn_widgets){?>
        <?=  $this->render('/partials/_social_network_block.php', ['current_category' => $current_category]) ?>
    <? } ?>
</div>
<script>
    function raiseAd(id){
        $.ajax({
            dataType: "json",
            type : 'POST',
            url: '<?= \yii\helpers\Url::toRoute('/ad/raise/') ?>',
            data: {id: id},
            success: function(data) {
                if(data != "error"){
                    $("#raise"+id).text("<?= __('Ad has been raised') ?>");
                    setTimeout(
                        function()
                        {
                            $("#raise"+id).remove();
                        }, 3000);
                }
            }
        });
    }
    function inactivateAd(id){
        $.ajax({
            dataType: "json",
            type : 'POST',
            url: '<?= \yii\helpers\Url::toRoute('/ad/deactivate/') ?>',
            data: {id: id},
            success: function(data) {
                if(data != "error"){
                    $("#active"+id).text("<?= __('Ad is inactive') ?>");
                    $("#small"+id).remove();
                    $("#raise"+id).remove();
                }
            }
        });
    }

    function repostAd(id){
        $.ajax({
            dataType: "json",
            type : 'POST',
            url: '<?= \yii\helpers\Url::toRoute('/ad/repost/') ?>',
            data: {id: id},
            success: function(data) {
                if(data != "error"){
                    $("#repost"+id).text("<?= __('Ad has been reposted') ?>");
                    setTimeout(
                        function()
                        {
                            $("#repost"+id).text("<?=__("Active to")?> "+data);
                        }, 3000);
                }
            }
        });
    }

    function moveToEdit(url){
        window.location = '/redaktirovat/'+url+'/';
    }
</script>