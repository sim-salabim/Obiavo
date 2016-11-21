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
        'panel-title' => 'Основныe данные',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'code:text:Код языка', 'model' => $language],
            ['attributes' => 'active:checkbox:Активность', 'model' => $language],
        ]
    ],
    [
        'panel-title' => 'Тексты',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'name:text:Название языка', 'model' => $text],
        ]
    ],
];

$saveUrl = $toUrl;

echo $this->render('/templates/form',compact('form','saveUrl'));