<div class="search">
    <!--<input type="text" class="form-control" placeholder="Введите город/регион..">-->
</div>

<div class="selectboxmenu-items js-scroll ">

<?php foreach ($cities as $city) { ?>
    
<span class="a-like">
    <span><b><?= $city->_text->name?></b> - <?= $city->region->_text->name?></span>
</span>

<?php } ?>

</div>
<script type="text/javascript">

Core.onFullLoad(function(){
    
    rct.mount('search-input',$('.search')[0]);

})
</script>