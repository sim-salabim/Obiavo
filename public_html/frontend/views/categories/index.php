<?php 
 use frontend\helpers\ArrayHelper;
 use yii\helpers\Url;
 
 $this->title = "Бесплатные объявления в " . Yii::$app->location->name_pp; 
?>
<div class="content-header">
    <div class="navbar">
        <?= \frontend\widgets\Selectpicker::widget([
            'values' => ['Купить','Продать','Аренда','Обменять','Объявления'],
            'name' => 'type_ads'
        ])?>

        <?= \frontend\widgets\Selectpicker::widget([
            'values' => ArrayHelper::make1Array($categories, 'categoriesText->name'),
            'name' => 'type_ads'
        ])?>

        <?= \frontend\widgets\Selectpicker::widget([
            'values' => ArrayHelper::make1Array(
                    \common\models\Category::getNextChilds($categories),
                    'categoriesText->name'
            ),
            'name' => 'type_ads'
        ])?>
    </div>

    <div class="description-text">
        <?= \Yii::$app->params['domain']?> - сайт бесплатных объявлений России. Ежедневно на сайте раpмещаются тысячи частных объявлений. 34454 - Объявления.
    </div>
</div>