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
        'panel-title' => 'Основныe данные',
        'columns' => [
            // attribute:typeField:label
            'techname:text:Тех. название',
            'active:checkbox:Активность',
            ['attributes' => 'code:text:Код языка', 'model' => $category],
            ['attributes' => 'code:text:Код языка', 'model' => $category],
        ]
    ],
    [
        'panel-title' => 'Сео данные',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'seo_name:text:Название', 'model' => $categoryGenerate],
            ['attributes' => 'url:text:URL', 'model' => $categoryGenerate],
            ['attributes' => 'seo_title:text:SEO заголовок', 'model' => $categoryGenerate],
            ['attributes' => 'seo_desc:text:SEO описание', 'model' => $categoryGenerate],
            ['attributes' => 'seo_keywords:text:SEO ключевые слова', 'model' => $categoryGenerate],
        ]
    ],
];

$saveUrl = $toUrl;

echo $this->render('/templates/form',compact('form','saveUrl'));
