
<? if(\frontend\components\Location::getDefaultLanguageId() == \common\models\Language::LANG_EN){?>
<p>
    Hello, <?= $user->getFullName() ?>.
</p>
<p>
    Please click to the following link to recover your password:
</p>
<p>
    <a href="<?= "https://".\frontend\components\Location::getCurrentDomain() ?>/reset?key=<?= $token ?>"><?= "https://".\frontend\components\Location::getCurrentDomain()."/reset?key=".$token ?></a>
</p>
<p>
    Sincerely yours Obiavo team
</p>
<p>
    <?= "https://".\frontend\components\Location::getCurrentDomain() ?>
</p>
<? }else{ ?>
    <p>
        Здравствуйте, <?= $user->getFullName() ?>.
    </p>
    <p>
        Для восстановления пароля пройдите по ссылке ниже:
    </p>
    <p>
        <a href="<?= "https://".\frontend\components\Location::getCurrentDomain() ?>/reset?key=<?= $token ?>"><?= "https://".\frontend\components\Location::getCurrentDomain()."/reset?key=".$token ?></a>
    </p>
    <p>
        С уважением команда Объяво
    </p>
    <p>
        <?= "https://".\frontend\components\Location::getCurrentDomain() ?>
    </p>
<? } ?>
