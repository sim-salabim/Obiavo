<?php
Use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use backend\widgets\TableList;
use yii\helpers\Html;

$this->title = 'Категории';
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">


    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['create','parent_id' => $categoryParent->id])?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Создать новый пункт
        </button>
    </div>

    <?= $this->render('breadcrumbs', ['category' => $categoryParent]);?>

<?= TableList::widget([
    'title' => 'Категории',
    'data'  => $categories,
    'columns' => [
        [
            'attribute'    => 'id',
            'label'        => 'ID',
        ],
        [
            'label'        => 'Название',
            'content'      => function($model){
                $html = Html::a($model->techname, Url::toRoute(['/categories','id' => $model->id]));
                $html .= backend\helpers\ActiveLabel::status($model->active, [
                        'active' => 'активно',
                        'inactive' => 'не активно'
                        ]);

                return $html;
            },
        ],
        [
            'label'        => 'Осцновная соц. группа',
            'content'      => function($model){
                              return ($model->socialNetworkGroupMain) ? $model->socialNetworkGroupMain->name : '<span class="badge badge-warning">Не выбранa</span>';
            },
        ],
        [
            'label'        => 'Тексты',
            'format'       => TableList::TYPE_MULTI_BUTTON,
        ],
        [
            'label'        => 'SEO типов',
            'format'       => TableList::TYPE_OPT_BUTTON,
            'buttons'      => [
                'update-seo-attached',
            ]
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