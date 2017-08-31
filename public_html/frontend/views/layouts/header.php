<nav class="navbar navbar-expand-md navbar-light fixed-top bg-light" style="border-bottom: 1px solid #c0c0c0;">
    <button class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarTogglerDemo01"
            aria-controls="navbarTogglerDemo01"
            aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item dropdown">
                <a class="nav-link"
                   href="/"
                   id="navbarDropdownMenuLink"
                   data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <ul>
                        <?php if (Yii::$app->user->isGuest) { ?>
                            <li><a href="<?= yii\helpers\Url::toRoute('/login')?>">Вход</a></li>
                            <li><a href="<?= yii\helpers\Url::toRoute('/registration')?>">Регистрация</a></li>
                        <?php } ?>

                        <?php if (!Yii::$app->user->isGuest) { ?>
                            <li><a href="<?= yii\helpers\Url::toRoute('/im')?>">Личный кабинет</a></li>
                            <li><a href="<?= yii\helpers\Url::toRoute('/logout')?>">Выйти</a></li>
                        <?php } ?>
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </div>
            </li>
            <a class="navbar-brand mx-2" href="#">Obiavo.ru</a>
        </ul>

        <form class="form-inline my-2 mr-4 my-lg-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        <span class="navbar-text mx-2">Россия</span>
        <button type="button" class="btn btn-success my-2">+ Подать объявление</button>
    </div>
</nav>