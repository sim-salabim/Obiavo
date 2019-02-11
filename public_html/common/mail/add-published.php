<? if(!$fast){?>
    <br>
        Здравствуйте, вы опубликовали объявление:</br>

        Ваше объявление: <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/".$add->url ?>"><?= $add->title ?></a></br>

        Для того чтобы редактировать, поднимать или снимать с публикации ваши объявления пройдите по ссылке предварительно пройдя авторизацию с вашим электронным адресом и паролем:</br>

    <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/moi-obiavleniya/" ?>"><?= __("My ads");?></a></br>

    С уважением команда Объяво</br>
    https://<?= \frontend\components\Location::getCurrentDomain() ?>
    </p>
<? }else{ ?>
    <br>
        Добро пожаловать на сайт бесплатных объявлений.</br>

        Ваш email: <?= $user->email ?></br>
        Ваш пароль: <?= $pass ?></br>

        Ваше объявление: <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/".$add->url ?>"><?= $add->title ?></a></br>

        Для того чтобы редактировать, поднимать или снимать с публикации ваши объявления пройдите по ссылке предварительно пройдя авторизацию с вашим электронным адресом и паролем:</br>

        <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/moi-obiavleniya/" ?>"><?= __("My ads");?></a></br>

        С уважением команда Объяво</br>
        https://<?= \frontend\components\Location::getCurrentDomain() ?>
    </p>
<? }?>
