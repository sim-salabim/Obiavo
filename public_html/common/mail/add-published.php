<? if(!$fast){?>
    <? if(\frontend\components\Location::getDefaultLanguageId() == \common\models\Language::LANG_EN){?>
    <p>
        Hello, we\'ve just posted your advertisement:</br>
    </p>
    <p>
        Your ad: <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/".$add->city->domain."/"."/".$add->url ?>/"><?= $add->title ?></a></br>
    </p>
    <p>
        To edit, raise or inactivate your ads please get authorised using your email and password then go to the following link:</br>
    </p>
    <p>
        <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/my-ads/" ?>"><?= __("My ads");?></a></br>
    </p>
    <p>
    Sincerely yours Obiavo team</br>
    https://<?= \frontend\components\Location::getCurrentDomain() ?>
    </p>
        <? }else{?>
        <p>
            Здравствуйте, вы опубликовали объявление:</br>
        </p>
        <p>
            Ваше объявление: <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/".$add->url ?>/"><?= $add->title ?></a></br>
        </p>
        <p>
            Для того чтобы редактировать, поднимать или снимать с публикации ваши объявления пройдите по ссылке предварительно пройдя авторизацию с вашим электронным адресом и паролем:</br>
        </p>
        <p>
            <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/moi-obiavleniya/" ?>"><?= __("My ads");?></a></br>
        </p>
        <p>
            С уважением команда Объяво</br>
            https://<?= \frontend\components\Location::getCurrentDomain() ?>
        </p>
        <? }?>
<? }else{ ?>
    <? if(\frontend\components\Location::getDefaultLanguageId() == \common\models\Language::LANG_EN){?>
    <p>
        Welcome to our site of free ads.</br>
    </p>
    <p>
        Your email: <?= $user->email ?></br>
        Your pass: <?= $pass ?></br>
    </p>
    <p>
        Your ad: <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/".$add->url ?>/"><?= $add->title ?></a></br>
    </p>
    <p>
        To edit, raise or inactivate your ads please get authorised using your email and password then go to the following link:</br>
    </p>
            <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/my-ads/" ?>"><?= __("My ads");?></a></br>

    <p>
        Sincerely yours Obiavo team</br>
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
<? }?>
