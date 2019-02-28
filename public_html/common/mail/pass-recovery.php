<p>
    Здравствуте, <?= $user->getFullName() ?>.
</p>
<p>
    Для восстановления пароля пройдите по ссылке ниже:
    <a href="/reset?key=<?= $token ?>"><?= "https://".\frontend\components\Location::getCurrentDomain()."/reset?key=".$token ?></a>
</p>
<p>
    С уважением команда Объяво
</p>
<p>
    <?= "https://".\frontend\components\Location::getCurrentDomain() ?>
</p>