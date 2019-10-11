<hr>
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
        <? $id = uniqid();?>
        <div id="<?= $id ?>" class="carousel slide"  data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <? if(count($ad->files)){ ?>
                    <? foreach($ad->files as $key => $image){ ?>
                        <div class="carousel-item <? if($key == 0){ ?> active<? } ?>">
                            <img
                                    class="d-block img-fluid"
                                    alt="<?= $ad->title." - ".__('photo') ?>"
                                    src="<?= $image->getImage(false)?>"
                                    title='<?= __('Ad').' "'.$ad->title.'" - '.__('photo') ?>'
                            >
                        </div>
                    <? } ?>
                <? }else{ ?>
                    <div class="carousel-item active">
                        <img class="d-block img-fluid" src="<?= $ad->avatar(false)?>">
                    </div>
                <? } ?>
            </div>
            <a class="carousel-control-prev" href="#<?= $id ?>" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#<?= $id ?>" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 nonpadding-left-items-media">
        <? if(time() >= $ad->expiry_date or !$ad->active){?>
<!--            <p>--><?//= __('Ad is inactive since')." ".$ad->getHumanDate(\common\models\Ads::DATE_TYPE_EXPIRATION).". ".__("All ads contacts are blocked.") ?><!--</p>-->
        <? } ?>
        <? if(($user and $user->is_admin) or ($user and $ad->users_id == $user->id) or (isset($_COOKIE['session_token']) and $_COOKIE['session_token'] == $ad->session_token)){?>
        <div>
            <button
                class="btn btn-danger my-1 width-100 edit-btn"
                id="edit_ad_button"
            ><?= __('Edit') ?></button>
        </div>
        <? } ?>
        <div class="price-title">
            <?= $ad->price . " ". Yii::$app->location->country->currency->_text->name_short ?>
        </div>
        <div >
            <button class="btn btn-success my-1 width-100 <? if($ad->active){?>show-number-button<? } ?>" >
                <? if(!$show_phone_number and $ad->active){?>
                    <?= __('Show phone number') ?><br/>
                    <?= cutText($ad->user->phone_number, 3, false)."-**-***-***" ?>
                <? }else if(($show_phone_number AND $show_phone_number == 1 and $ad->active) ){//and (time() < $ad->expiry_date and $ad->active)){ ?>
                    <?= $ad->user->phone_number ?>
                <? }else{ ?>
                    <?= __('Show phone number') ?><br/>
                    <?= cutText($ad->user->phone_number, 3, false)."-**-***-***" ?>
                <? } ?>
            </button>
        </div>
        <div class="w-100"></div>
        <?
        $advertising_code = \common\models\Advertising::getCodeByPlacement(\common\models\Advertising::PLACEMENT_AD_PAGE_ABOVE_CONTACTS_BLOCK);
        ?>
        <? if($advertising_code ){ ?>
            <div class="col-lg-12 col-sm-4 nonpadding-left-items">
                <?= $advertising_code; ?>
            </div>
        <? } ?>
        <div class="col-lg-12 col-sm-4 nonpadding-left-items">
            <?= $ad->user->first_name ?>
        </div>
        <div class="col-lg-12  col-sm-4 nonpadding-left-items">
            <?= $ad->user->city->region->_text->name ?>,
            <?= $ad->user->city->_text->name ?>
        </div>
        <div class="col-lg-12  col-sm-12 nonpadding-left-items">
            <?= date('h:i', $ad->created_at)." ".date('d/m/Y', $ad->created_at) ?>
        </div>
        <br/>
        <div class="col-lg-12  col-sm-12 nonpadding-left-items">
            <?= __('Share it') ?>
        </div>
        <div class="col-lg-12  col-sm-12 nonpadding-left-items">
            <script type="text/javascript">(function() {
                    if (window.pluso)if (typeof window.pluso.start == "function") return;
                    if (window.ifpluso==undefined) { window.ifpluso = 1;
                        var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                        s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                        s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
                        var h=d[g]('body')[0];
                        h.appendChild(s);
                    }})();</script>
            <div class="pluso" data-background="transparent" data-options="medium,square,line,horizontal,nocounter,theme=01" data-services="vkontakte,odnoklassniki,facebook,twitter"></div>
        </div>
        <?
        $advertising_code = \common\models\Advertising::getCodeByPlacement(\common\models\Advertising::PLACEMENT_AD_PAGE_BELOW_CONTACTS_BLOCK);
        ?>
        <? if($advertising_code ){ ?>
            <div class="col-lg-12  col-sm-12">
                <?= $advertising_code; ?>
            </div>
        <? } ?>
    </div>
    <div class="col-12"><hr></div>
    <?
    $advertising_code = \common\models\Advertising::getCodeByPlacement(\common\models\Advertising::PLACEMENT_AD_PAGE_ABOVE_TEXT_BLOCK);
    ?>
    <? if($advertising_code ){ ?>
        <div class="col-12">
            <?= $advertising_code; ?>
        </div>
    <? } ?>
    <div class="col-12">
        <pre class="text_font"><?= $ad->text ?></pre>
    </div>
    <?
    $advertising_code = \common\models\Advertising::getCodeByPlacement(\common\models\Advertising::PLACEMENT_AD_PAGE_BELOW_TEXT_BLOCK);
    ?>
    <? if($advertising_code ){ ?>
        <div class="col-12">
            <?= $advertising_code; ?>
        </div>
    <? } ?>
</div>
<script>
    $(document).ready(function(){
        $('#<?= $id ?>').carousel()
        $('.show-number-button').bind('click', function(){
            window.location.href = window.location.href.split('?')[0] + '?show_phone_number=1';
        });
        $('#edit_ad_button').bind('click', function(){
            window.location = '/redaktirovat/<?= $ad->url ?>/';
        });
    });
</script>