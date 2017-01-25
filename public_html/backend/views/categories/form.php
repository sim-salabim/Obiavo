<?php
/**
 * Форма для добавления и редактирования пунктов категорий
 * @var object $category - Объект редактируемой категории (пустой объект, если добавляем)
 * @var object $categoryGenerate - Объект из связанной модели сгерерированных категорий
 * @var string toUrl ссылку куда отправлять данные на обработку
 */

use yii\helpers\Url;
use yii\bootstrap\Html;
use frontend\helpers\ArrayHelper;

$placements = common\models\Placement::find()->withText()->all();

$form = [
    [
        'panel-title' => 'Основныe данные',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'techname:text:Тех. название', 'model' => $category],
            ['attributes' => 'placements:select-multiple:Типы размещения объявлений', 'model' => $category,
                'options' => [
                    'multiple' => [
                        'elements' => ArrayHelper::map($placements, 'id','_text.name'),
                        'selected' => ArrayHelper::getColumn($category->placements,'id')
                    ]
                ]
            ],
            ['attributes' => 'active:checkbox:Активность', 'model' => $category],
        ]
    ],
    [
        'panel-title' => 'Сео данные',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'name:text:Название', 'model' => $categoriesText],
            ['attributes' => 'url:text:URL', 'model' => $categoriesText],
            ['attributes' => 'seo_title:text:SEO заголовок', 'model' => $categoriesText],
            ['attributes' => 'seo_desc:text:SEO описание', 'model' => $categoriesText],
            ['attributes' => 'seo_keywords:text:SEO ключевые слова', 'model' => $categoriesText],
        ]
    ],
];

$saveUrl = $toUrl;

echo $this->render('/templates/form',compact('form','saveUrl'));
?>
<script type="text/javascript">
//    $('.selectpicker').selectpicker()
    $('.selectpicker').selectpicker();
</script>