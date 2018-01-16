<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
Use yii\helpers\Url;
use backend\widgets\TableList;

$breadcrumbs = '';
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">
    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['create'])?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Создать новую сеть
        </button>
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['sn-order'])?>"
                id="order_settings"
        >
            <i class="fa fa-fw -square -circle fa-arrows-v"></i>
            Редактировать порядок
        </button>
    </div>

    <?= $breadcrumbs;?>

<?= TableList::widget([
    'title' => 'Социальные сети',
    'data'  => $social_networks,
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
            'label'        => 'Группа по умолчанию',
            'content'      => function($model){
                $html = ($model->default) ? $model->default->name : '<span class="badge badge-warning">Не выбранa</span>';
                return $html;
            },
        ],
        [
            'label'        => 'Активность',
            'content'      => function($model){
                $html = ($model->active) ? '<span class="btn btn-success btn-xs">Активно</span>' : '<span class="btn btn-danger btn-xs">Неактивно</span>';
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
