<? if(!$fast){?>
    <p>
        Здравствуйте, вы опубликовали объявление:</br>

        Ваше объявление: <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/".$add->url ?>/"><?= $add->title ?></a></br>

        Для того чтобы редактировать, поднимать или снимать с публикации ваши объявления пройдите по ссылке предварительно пройдя авторизацию с вашим электронным адресом и паролем:</br>

    <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/moi-obiavleniya/" ?>"><?= __("My ads");?></a></br>

    С уважением команда Объяво</br>
    https://<?= \frontend\components\Location::getCurrentDomain() ?>
    </p>
<? }else{ ?>
    <p>
        Добро пожаловать на сайт бесплатных объявлений.</br>
    </p>
    <p>
        Ваш email: <?= $user->email ?></br>
        Ваш пароль: <?= $pass ?></br>
    </p>
    <p>
        Ваше объявление: <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/".$add->url ?>/"><?= $add->title ?></a></br>
    </p>
    <p>
        Для того чтобы редактировать, поднимать или снимать с публикации ваши объявления пройдите по ссылке предварительно пройдя авторизацию с вашим электронным адресом и паролем:</br>
    </p>
        <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/moi-obiavleniya/" ?>"><?= __("My ads");?></a></br>
    <p>
        С уважением команда Объяво</br>
        https://<?= \frontend\components\Location::getCurrentDomain() ?>
    </p>
<? }?>
