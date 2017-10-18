<?php
Use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use backend\widgets\TableList;
use yii\helpers\Html;

$this->title = 'CMS страницы';
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">


    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['create'])?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Создать новую страницу
        </button>
    </div>


    <?= TableList::widget([
        'title' => 'CMS страницы',
        'data'  => $cms_pages,
        'columns' => [
            [
                'attribute'    => 'id',
                'label'        => 'ID',
            ],
            [
                'label'        => 'Название',
                'content'      => function($model){
                    $html = Html::a($model->techname, Url::toRoute(['/cms','id' => $model->id]));

                    return $html;
                },
            ],
            [
                'label'        => 'Тексты',
                'format'       => TableList::TYPE_MULTI_BUTTON,
            ],
            [
                'label'        => 'Управление',
                'format'       => TableList::TYPE_OPT_BUTTON,
                'buttons'      => [
                    'update',
                    'delete',
                ]
            ],
        ]
    ]);?>

</div>