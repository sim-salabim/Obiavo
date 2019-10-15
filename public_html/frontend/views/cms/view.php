<div class="row">
    <?
    $advertising_code = \common\models\Advertising::getCodeByPlacement(\common\models\Advertising::PLACEMENT_TECHNICAL_PAGES_ABOVE_TEXT);
    ?>
    <? if($advertising_code ){ ?>
        <div class="col-lg-12">
            <?= $advertising_code; ?>
        </div>
    <? } ?>
    <div class="col-12">
        <?= $page->seo_text ?>
    </div>
    <?
    $advertising_code = \common\models\Advertising::getCodeByPlacement(\common\models\Advertising::PLACEMENT_TECHNICAL_PAGES_ABOVE_TEXT);
    ?>
    <? if($advertising_code ){ ?>
        <div class="col-lg-12">
            <?= $advertising_code; ?>
        </div>
    <? } ?>
</div>