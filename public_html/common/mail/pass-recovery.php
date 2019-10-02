<p>
    <?= __("Hello")  ?>, <?= $user->getFullName() ?>.
</p>
<p>
    <?= __("Please click to the following link to recover your password") ?>:
</p>
<p>
    <a href="/reset?key=<?= $token ?>"><?= "https://".\frontend\components\Location::getCurrentDomain()."/reset?key=".$token ?></a>
</p>
<p>
    <?= __('Sincerely yours Obiavo team'); ?>
</p>
<p>
    <?= "https://".\frontend\components\Location::getCurrentDomain() ?>
</p>