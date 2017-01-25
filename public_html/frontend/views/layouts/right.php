
<div class="aside-right">
    <button class="btn-change">Подать объявление</button>

    <div class="hr-black"></div>

    <div class="sidebar-menu">
        <ul class="list-unstyled">
            <?php if (Yii::$app->user->isGuest) { ?>
            <li><a href="<?= yii\helpers\Url::toRoute('/login')?>">Вход</a></li>
            <li><a href="<?= yii\helpers\Url::toRoute('/registration')?>">Регистрация</a></li>
            <?php } ?>

            <?php if (!Yii::$app->user->isGuest) { ?>
            <li><a href="<?= yii\helpers\Url::toRoute('/im')?>">Личный кабинет</a></li>
            <li><a href="<?= yii\helpers\Url::toRoute('/logout')?>">Выйти</a></li>
            <?php } ?>
        </ul>
    </div>
</div>