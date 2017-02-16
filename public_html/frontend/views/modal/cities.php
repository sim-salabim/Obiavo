<?php
use frontend\helpers\ArrayHelper;
use yii\helpers\Json;
?>

<div id="cities-region-list-container">
</div>

<script type="text/javascript">

Core.onFullLoad(function(){

    rct.mount('search-cities-region-list',$('#cities-region-list-container')[0],{
        cities  : <?= Json::encode($cities);?>,
        url     : '<?= \yii\helpers\Url::toRoute('/cities/search-cities');?>'
    });

})
</script>