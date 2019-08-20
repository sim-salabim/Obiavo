
<header class="header" id="main-header">
    <nav class="navbar navbar-expand navbar-light nav-sm-res navbar-fixed-top bg-light">
        <div class="container nav-container">
        <button class="navbar-toggler"
                type="button"
                onclick="openNav()"
                data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav nav-ul mr-auto mt-lg-0">
<!--                <li>-->
<!--                    <a class="nav-link" href="javascript:openNav()" id="navbarDropdownMenuLink">-->
<!--                        <span class="navbar-toggler-icon"></span>-->
<!--                    </a>-->
<!--                </li>-->
                <li>
                    <img src="/logo.png" width="27" alt="<?= __('Obiavo - advertisement site'); ?>" class="logo-img">
                    <a
                        class="navbar-brand mx-2 root-url"
                        title="<?= __('Go to the main page fo free ads board of')." ".$pp_place ?>"
                        href="<?= $location_domain ?>"
                    >
                        <?= ucfirst(Yii::$app->location->country->domain) ?>
                    </a>
                </li>
            </ul>
            <div class="dropdown show d-md-none d-lg-none">
                <a class="btn btn-secondary"
                   href="#" role="button"
                   id="dropdownSearchLink"
                   data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">
                    <span class="fa fa-search"></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-menu-search show"
                     aria-labelledby="dropdownSearchLink">
                    <div class="form-inline my-2 mr-4 my-lg-0">
                        <input class="form-control mr-sm-2 search-input-dropdown"
                               type="text" placeholder="<?= __('Search') ?>"
                               aria-label="<?= __('Search') ?>">
                        <button class="btn btn-secondary my-2 search-button-dropdown">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-inline my-2 my-lg-0 d-none d-md-block d-lg-block" style="margin-bottom: 0 !important; margin-top: 0 !important">
                <input class="form-control mr-sm-2 search-input"
                       type="text"
                       placeholder="<?= __('Search') ?>"
                       aria-label="<?= __('Search') ?>">
                <button class="btn btn-secondary my-2 search-button">
                    <i class=" fa fa-search "></i>
                </button>
            </div>
            <?
            $application_url = '';
            if(isset($this->params['application_url']) AND $this->params['application_url']){
                $application_url = $region_add.$this->params['application_url'];
            }else{
                $application_url = yii\helpers\Url::toRoute($region_add."/".\common\models\Ads::generateApplicationUrl());
            }?>
            <ul class="navbar-nav nav-ul mr-right mt-lg-0">
            <? if (Yii::$app->user->isGuest) { ?>
                <li class="btn-group" role="group">
                    <button
                            type="button"
                            class="btn-user-enter my-2 d-block d-md-block d-lg-block no-transition"
                            id="login-btn"><?= __('Sign in') ?>
                    </button>

                </li>
            <? }else{ ?>
                <ul class="navbar-nav nav-ul mr-right mt-lg-0">
                    <li class="btn-group" role="group">
                        <button
                                type="button"
                                class="btn-user  my-2 d-block d-md-block d-lg-block no-transition"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <span class="btn-user-user fa fa-user" aria-hidden="true"></span>
                            <span class="btn-user-down fa fa-caret-down" aria-hidden="true"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <span class="btn-user-menu-icon fa fa-cog fa-fw" aria-hidden="true"></span>
                                <a href="<?= yii\helpers\Url::toRoute('/im') ?>"> <?= __('Personal office') ?></a>
                            </li>
                            <li>
                                <span class="btn-user-menu-icon fa fa-edit fa-fw" aria-hidden="true">
                                    <a href="<?= yii\helpers\Url::toRoute('/moi-obiavleniya') ?>"></a>
                                </span>
                                <a href="<?= yii\helpers\Url::toRoute('/moi-obiavleniya') ?>"> <?= __('My ads') ?></a>
                            </li>
                            <li>
                                <span class="btn-user-menu-icon fa fa-sign-out fa-fw" aria-hidden="true">
                                    <a href="<?= yii\helpers\Url::toRoute('/logout') ?>"></a>
                                </span>
                                <a href="<?= yii\helpers\Url::toRoute('/logout') ?>"> <?= __('Logout') ?></a>
                            </li>
                        </ul>
                    </li>
            <? } ?>

            <li>
                <a
                href="<?= $application_url ?>"
                title="<?= __('Post your ad in')." ".$in_place." ".__('free without registration') ?>"
                class="btn btn-success my-2 d-none d-md-block d-lg-block new-add-btn no-transition">+ <?= __('Post an add') ?></a>
            </li>
                <li>
            <a
                href="<?= $application_url ?>"
                title="<?= __('Post your ad in')." ".$in_place." ".__('free without registration') ?>"
                class="btn btn-success d-block my-2 d-md-none d-lg-none new-add-btn no-transition">+ </a>
                </li>
            </ul>
        </div>
        </div>
    </nav>
</header>
<?
$ad_href = \yii\helpers\Url::toRoute('/podat-obiavlenie');
?>
<script>
    $(document).ready(function(){
        $('.new-add-btn').bind('click', function(){
            console.log('<?= $ad_href ?>');
            window.location.href = '<?= $ad_href ?>';
        });
        $('.search-button').bind('click', function(){
            var query = $('.search-input-dropdown').val();
            if(query == ''){
                query = $('.search-input').val();
            }
            var urlArr = window.location.href.split('?');
            var getParams = [];
            if(urlArr[1]){
                var params = urlArr[1].split('&');
                params.forEach(function(item){
                    var get = item.split('=');
                    if(get[0] != 'page'){
                        getParams[get[0]] = get[1];
                    }
                });
            }
            getParams['query'] = query;
            var getString = '?';
            for (var key in getParams) {
                getString += key+"="+getParams[key]+'&';
            }
            getString = getString.substring(0, getString.length - 1);
            window.location.href = window.location.origin+ "/poisk/" + getString
        });
        <?php if (Yii::$app->user->isGuest) { ?>
            $('#login-btn').bind('click', function(){
                window.location.href = '<?= yii\helpers\Url::toRoute('/login') ?>';
            });
        <? } ?>
    });
</script>