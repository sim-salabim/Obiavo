<?
$current_url = Yii::$app->request->url;

?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" <? if(strpos($current_url, 'index') !== false){?>class="active"<? } ?>>
        <a href="/moderation/index" aria-controls="moderation" role="tab" >На модерации</a>
    </li>
    <li role="presentation" <? if(strpos($current_url, 'moderated') !== false){?>class="active"<? } ?>>
        <a href="/moderation/moderated" aria-controls="moderated" role="tab" >Прошедшие модерацию</a>
    </li>
    <li role="presentation"  <? if(strpos($current_url, 'all') !== false){?>class="active"<? } ?>>
        <a href="/moderation/all" aria-controls="all" role="tab" >Все обьявления</a>
    </li>
</ul>