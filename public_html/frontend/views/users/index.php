<?php
use yii\helpers\Url;
$this->title = __('My settings');
$settings_route = Url::toRoute(["/nastroiki"]);
if(\frontend\components\Location::getDefaultLanguageId() == \common\models\Language::LANG_EN){
    $settings_route = Url::toRoute(["/settings"]);
}
?>
<a href="<?= Url::toRoute([$settings_route]); ?>"><?= __('My settings'); ?></a>