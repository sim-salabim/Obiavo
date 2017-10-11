<div class="row">
    <div class="col-lg-8 col-md-6 col-sm-12 text-align-left">
        <?= $title ?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 d-flex justify-content-end">
        <?=  $this->render('/partials/_filter_select.php',
            []) ?>
    </div>
</div>
<hr>
<div class="row">
    <? if(!count($ads)){?>
        <div class="col-12">
            <?= $no_ads_title; ?>
        </div>
    <? }else{?>
        <? foreach($ads as $ad){?>
            <div class="col-lg-2">
                <img class="img-fluid" src="<?= $ad->avatar(true) ?>">
            </div>
            <div class="col-lg-10">
                <span><strong><?= $ad->title ?></strong></span></br>
                <span><small><?= $ad->price . " " . \common\models\Ads::PRICE_LABEL ?></small></span></br>
                <span><small><?= $ad->text ?></small></span></br>
            </div>
        <? } ?>
    <? } ?>
</div>