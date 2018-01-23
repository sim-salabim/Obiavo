<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
use backend\widgets\TableList;
use yii\helpers\Url;

$breadcrumbs = '';
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">


    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['create'])?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Создать новый пункт
        </button>
    </div>

    <?= $breadcrumbs;?>

<?= TableList::widget([
    'title' => 'Основные группы',
    'data'  => $main_groups,
    'columns' => [
        [
            'attribute'    => 'id',
            'label'        => 'ID',
        ],
        [
            'label'        => 'Название',
            'content'      => function($model){
                $html = $model->name;
                return $html;
            },
        ],
        [
            'label'        => 'По умолчанию',
            'content'      => function($model){
                $html = '';
                if($model->as_default) $html = '<span class="btn btn-success btn-xs">По умолчанию</span>';
                return $html;
            },
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