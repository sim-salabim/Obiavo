<header class="header">
    <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light"
         style="border-bottom: 1px solid #c0c0c0;">
        <button class="navbar-toggler"
                type="button"
                onclick="openNav()"
                data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <a class="nav-link"
                   href="javascript:openNav()"
                   id="navbarDropdownMenuLink"">
                <span class="navbar-toggler-icon"></span>
                </a>
                <a class="navbar-brand mx-2" href="<?= yii\helpers\Url::toRoute('/') ?>">Obiavo.ru</a>
            </ul>

            <form class="form-inline my-2 mr-4 my-lg-0">
                <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><?= __('Search') ?></button>
            </form>
            <span class="navbar-text mx-2">Россия</span>
            <button type="button" class="btn btn-success my-2">+ <?= __('Post an add') ?></button>
        </div>
    </nav>
</header>
<div  id="mySidenav">
    <ul class="sidenav">
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
            <li><a href="<?= yii\helpers\Url::toRoute('/logout') ?>"><?= __('Logout') ?></a></li>
        <?php } ?>

    </ul>
</div>