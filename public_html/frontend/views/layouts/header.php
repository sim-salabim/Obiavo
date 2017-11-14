<header class="header">
    <nav class="navbar navbar-expand navbar-light navbar-fixed-top bg-light">
        <button class="navbar-toggler"
                type="button"
                onclick="openNav()"
                data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <a class="nav-link"
                   href="javascript:openNav()"
                   id="navbarDropdownMenuLink"">
                <span class="navbar-toggler-icon"></span>
                </a>
                <a class="navbar-brand mx-2" href="<?= \frontend\helpers\LocationHelper::getDomainForUrl('/') ?>">Obiavo.ru</a>
            </ul>

            <div class="dropdown show d-md-none d-lg-none">
                <a class="btn btn-secondary"
                   href="#" role="button"
                   id="dropdownSearchLink"
                   data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">
                    <i class="fa fa-search"></i>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownSearchLink">
                    <div class="form-inline my-2 mr-4 my-lg-0">
                        <input class="form-control mr-sm-2" type="text" id="search-input" placeholder="<?= __('Search') ?>" aria-label="Search">
                        <button class="btn btn-secondary my-2 search-button">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-inline my-2 mr-4 my-lg-0 d-none d-md-block d-lg-block">
                <input class="form-control mr-sm-2"
                       type="text"
                       id="search-input"
                       placeholder="<?= __('Search') ?>"
                       aria-label="Search">
                <button class="btn btn-secondary my-2 search-button">
                    <i class="fa fa-search"></i>
                </button>
            </div>
            <span class="navbar-text mx-2 text-dark"><a href="<?= yii\helpers\Url::toRoute('/vybor-goroda') ?>"><?
                if(Yii::$app->location->city){
                    echo Yii::$app->location->city->_text->name;
                }else{
                    if(Yii::$app->location->region){
                        echo Yii::$app->location->region->_text->short_name;
                    }else{
                        echo 'Россия';
                    }
                }

                ?></a></span>

            <button type="button" class="btn btn-success my-2 d-none d-md-block d-lg-block new-add-btn">+ <?= __('Post an add') ?></button>

            <button type="button" class="btn btn-success d-block my-2 d-md-none d-lg-none new-add-btn">+ </button>
        </div>
    </nav>
</header>
<div  id="mySidenav">
    <ul class=" sidebar sidenav navbar-nav mr-auto">
        <?php if (Yii::$app->user->isGuest) { ?>
            <li><a href="<?= yii\helpers\Url::toRoute('/login') ?>"><?= __('Login') ?></a></li>
            <li><a href="<?= yii\helpers\Url::toRoute('/registration') ?>"><?= __('Registration') ?></a></li>
        <?php } ?>
        <li><a href="<?= yii\helpers\Url::toRoute('/vybor-goroda') ?>"><?= __('_Location') ?></a></li>
        <?php if (!Yii::$app->user->isGuest) { ?>
            <li>
                <a href="<?= yii\helpers\Url::toRoute('/im') ?>">
                    <?= __('My office') ?>
                </a>
            </li>
            <li>
                <a href="<?= yii\helpers\Url::toRoute('/moi-obiavleniya') ?>">
                    <?= __('My ads') ?>
                </a>
            </li>
            <li>
                <a href="<?= yii\helpers\Url::toRoute('/podat-obiavlenie') ?>">
                    <?= __('Add ad') ?>
                </a>
            </li>
            <li><a href="<?= yii\helpers\Url::toRoute('/logout') ?>"><?= __('Logout') ?></a></li>
        <?php } ?>

    </ul>
    <hr>
</div>
<?
$ad_href = \yii\helpers\Url::toRoute('/podat-obiavlenie');
if(Yii::$app->user->isGuest){
    $ad_href = \yii\helpers\Url::toRoute('/login');
}
?>
<script>
    $(document).ready(function(){
        $('.new-add-btn').bind('click', function(){
            window.location.href = '<?= $ad_href ?>';
        });
        $('.search-button').bind('click', function(){
            console.log(window.location.origin);
            var query = $('#search-input').val();
            var urlArr = window.location.href.split('?');
            var getParams = [];
            if(urlArr[1]){
                var params = urlArr[1].split('&');
                params.forEach(function(item){
                    var get = item.split('=');
                    getParams[get[0]] = get[1];
                });
            }
            getParams['query'] = query;
            var getString = '?';
            for (var key in getParams) {
                getString += key+"="+getParams[key]+'&';
            }
            getString = getString.substring(0, getString.length - 1);
            window.location.href = window.location.origin+ "/poisk" + getString
        });
    });
</script>