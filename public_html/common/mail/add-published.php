<? if(!$fast){?>
    <p>
        <?= _('Hello, we\'ve just posted your advertisement') ?>:</br>
    </p>
    <p>
        <?= __('Your ad');?>: <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/".$add->city->domain."/"."/".$add->url ?>/"><?= $add->title ?></a></br>
    </p>
    <p>
        <?= __('To edit, raise or inactivate your ads please get authorised using your email and password then go to the following link'); ?>:</br>
    </p>
    <p>
        <? if(\frontend\components\Location::getDefaultLanguageId() == \common\models\Language::LANG_RU){?>
            <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/moi-obiavleniya/" ?>"><?= __("My ads");?></a></br>
        <? }else{?>
            <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/my-ads/" ?>"><?= __("My ads");?></a></br>
        <? } ?>
    </p>
    <p>
    <?= __('Sincerely yours Obiavo team'); ?></br>
    https://<?= \frontend\components\Location::getCurrentDomain() ?>
    </p>
<? }else{ ?>
    <p>
        <?= __('Welcome to our site of free ads'); ?>.</br>
    </p>
    <p>
        <?= __('Your _email'); ?>: <?= $user->email ?></br>
        <?= __('Your pass'); ?>: <?= $pass ?></br>
    </p>
    <p>
        <?= __('Your ad'); ?>: <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/".$add->url ?>/"><?= $add->title ?></a></br>
    </p>
    <p>
        <?= __('To edit, raise or inactivate your ads please get authorised using your email and password then go to the following link'); ?>:</br>
    </p>
        <? if(\frontend\components\Location::getDefaultLanguageId() == \common\models\Language::LANG_RU){?>
            <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/moi-obiavleniya/" ?>"><?= __("My ads");?></a></br>
        <?}else{?>
            <a href="https://<?= \frontend\components\Location::getCurrentDomain()."/my-ads/" ?>"><?= __("My ads");?></a></br>
        <? } ?>
    <p>
        <?= __('Sincerely yours Obiavo team');?></br>
        https://<?= \frontend\components\Location::getCurrentDomain() ?>
    </p>
<? }?>
