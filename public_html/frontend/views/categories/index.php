<?php
 use frontend\helpers\ArrayHelper;
 use yii\helpers\Url;
 use common\models\Category;
 use frontend\widgets\Selectpicker;

 $this->title = "Бесплатные объявления в " . Yii::$app->location->name_pp;
?>
<div class="content-header">
    <div class="navbar">
        <?= Selectpicker::widget([
            'values' => ArrayHelper::map(
                                $placements,
                                function($el){
                                    return Url::toRoute(['categories/index','placement' => $el->_text->url]);
                                },
                                '_text.name'),
            'selected' => [Yii::$app->request->url],
            'name' => 'placements',
            'options' => ['class' => 'redirect']
        ])?>

        <?= Selectpicker::widget([
            'values' => ArrayHelper::map(
                                $categories,
                                function($el){
                                    return Url::toRoute(['categories/index','category' => $el->_text->url]);
                                },
                                '_text.name'),
            'name' => 'category',
            'selected' => [Yii::$app->request->url],
            'options' => ['class' => 'redirect']
        ])?>

        <?php
            $nextChilds = Category::getNextChilds($categories);

            if ($nextChilds) {
        ?>
        <?= Selectpicker::widget([
            'values' => ArrayHelper::map(
                    $nextChilds,
                    'id',
                    '_text.name'
            ),
            'name' => 'category-childs'
        ])?>

        <?php } ?>
    </div>

    <div class="description-text">
        <?= \Yii::$app->params['domain']?> - сайт бесплатных объявлений России. Ежедневно на сайте раpмещаются тысячи частных объявлений. 34454 - Объявления.
    </div>
</div>

<?= $this->render('list', compact('categories'));?>