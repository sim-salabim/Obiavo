<?php
use yii\helpers\Url;
?>
<div class="row">
    <?php foreach ($regions as $region) { ?>
        <div class="col-lg-4 col-xs-2">
            <ul class="lvl-block">
                <h4 class="lvl-1">
                    <a href="<?= Url::toRoute(["select-location/$region->domain/"])?>"><?= $region->_text->name?></a>
                </h4>
                <?php foreach ($region->cities as $city) { ?>
                    <li class="lvl-2 ml-4 city-list"><a href="<?= Url::toRoute(["select-location/$city->domain/"])?>"><?= $city->_text->name?></a></li>
                <?php } ?>
            </ul>
        </div>

    <?php } ?>
</div>