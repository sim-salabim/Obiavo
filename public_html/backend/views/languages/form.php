<?php
/**
 * Форма для добавления и редактирования языков
 * @var object $language - Объект редактируемого языка (пустой объект, если добавляем)
 * @var string toUrl ссылку куда отправлять данные на обработку
 */

use yii\helpers\Url;
use yii\bootstrap\Html;

$textLang = $language->getText()->one();

$text = $textLang ? $textLang : new \common\models\LanguageText;

$form = [
    [
        'model' => $language,
        'panel-title' => 'Основныe данные',
        'columns' => [
            // attribute:typeField:label
            'code:text:Код языка',
            'active:checkbox:Активность',
        ]
    ],
    [
        'model' => $text,
        'panel-title' => 'Тексты',
        'columns' => [
            // attribute:typeField:label
            'name:text:Название языка',
        ]
    ],
];

$saveUrl = $toUrl;

echo $this->render('/templates/form',compact('form','saveUrl'));