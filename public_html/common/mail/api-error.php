<p>
    <?= $message ?></br>
    <? if (isset($error)){?>
        Код ошибки: <?= $error->error_code ?></br>
        Сообщение: <?= $error->error_msg ?></br>
    <? }?>
    <? if (isset($request)){?>
        Запрос: <?= $request ?>
    <? } ?>
    <? if(isset($details)){?>Детали: </br> <?= $details?> <? } ?>
</p>