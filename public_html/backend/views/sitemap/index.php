<?php
Use yii\helpers\Url;
use backend\widgets\TableList;
use yii\helpers\Html;
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">
    <div class="well">
        <a href="<?= Url::toRoute(['start'])?>">
            Запустить выполнение
        </a>
    </div>

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
                $html = Html::a($model->_text->name, Url::toRoute(['/regions','country_id' => $model->id]));

                return $html;
            },
        ],
        [
            'label'        => 'Управление',
            'format'       => TableList::TYPE_OPT_BUTTON,
            'buttons'      => [
                'run'
            ]
        ],
    ]
]);?>

</div>