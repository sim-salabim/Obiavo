<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-light">
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
                <a class="nav-link dropdown-toggle"
                   href="http://example.com"
                   id="navbarDropdownMenuLink"
                   data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">
                    Dropdown link
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <?php if (Yii::$app->user->isGuest) { ?>
            <a href="<?= yii\helpers\Url::toRoute('/login')?>">Вход</a>
            <a href="<?= yii\helpers\Url::toRoute('/registration')?>">Регистрация</a>
            <?php } ?>

            <?php if (!Yii::$app->user->isGuest) { ?>
                <a href="<?= yii\helpers\Url::toRoute('/im')?>">Личный кабинет</a>
                <a href="<?= yii\helpers\Url::toRoute('/logout')?>">Выйти</a>
            <?php } ?>
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>

        </ul>
        <a class="navbar-brand" href="#">Obiavo.ru</a>


        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        <button type="button" class="btn btn-success">Подать объявление</button>
    </div>
</nav>//*