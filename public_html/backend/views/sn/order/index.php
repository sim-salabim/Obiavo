<?php
Use yii\helpers\Url;
use backend\widgets\TableList;
use yii\helpers\Html;
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">

    <?= TableList::widget([
        'title' => 'Страны',
        'data'  => $countries,
        'columns' => [
            [
                'attribute'    => 'id',
                'label'        => 'ID',
            ],
            [
                'label'        => 'Название',
                'content'      => function($model){
                    $html = Html::a($model->_text->name, Url::toRoute(['/cities/city-order','country_id' => $model->id]));
                    return $html;
                },
            ],
        ]
    ]);?>

</div>