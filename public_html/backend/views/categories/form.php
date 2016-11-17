<?php
/**
 * Форма для добавления и редактирования пунктов категорий
 * @var object $category - Объект редактируемой категории (пустой объект, если добавляем)
 * @var object $categoryGenerate - Объект из связанной модели сгерерированных категорий
 * @var string toUrl ссылку куда отправлять данные на обработку
 */

use yii\helpers\Url;
use yii\bootstrap\Html;

$form = [
    [
        'model' => $category,
        'panel-title' => 'Основныe данные',
        'columns' => [
            // attribute:typeField:label
            'techname:text:Тех. название',
            'active:checkbox:Активность',
        ]
    ],
    [
        'model' => $categoryGenerate,
        'panel-title' => 'Сео данные',
        'columns' => [
            // attribute:typeField:label
            'seo_name:text:Название',
            'url:text:URL',
            'seo_title:text:SEO заголовок',
            'seo_desc:text:SEO описание',
            'seo_keywords:text:SEO ключевые слова',
        ]
    ],
];

$saveUrl = $toUrl;

echo $this->render('/templates/form',compact('form','saveUrl'));
