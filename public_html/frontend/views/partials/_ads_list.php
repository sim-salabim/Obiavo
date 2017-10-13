<div class="row">
    <div class="col-lg-8 col-md-6 col-sm-12 text-align-left">
        <?= $title ?>
    </div>
    <div class="col-lg-3 col-md-5 col-sm-12 d-flex justify-content-end">
        <?=  $this->render('/partials/_filter_select.php',
            []) ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-12">
        <?= $this->render('/partials/_grid_settings.php', []); ?>
    </div>
</div>
<hr>
<div class="row">
    <? if($ads_search['count'] == 0){?>
        <div class="col-12">
            <?= $no_ads_title; ?>
        </div>
    <? }else{?>
        <? foreach($ads_search['items'] as $ad){?>
            <div class="col-lg-2">
                <img class="img-fluid" src="<?= $ad->avatar(true) ?>">
            </div>
            <div class="col-lg-10">
                <span><strong><?= $ad->title ?></strong></span></br>
                <span><small><?= $ad->price . " " . \common\models\Ads::PRICE_LABEL ?></small></span></br>
                <span><small><?= $ad->text ?></small></span></br>
            </div>
        <? } ?>
        <? if(!($loaded >= $ads_search['count'])){?>
            <?= $this->render('/partials/_load_list.php', ['loaded' => $loaded])?>
        <? } ?>
    <? } ?>
</div>