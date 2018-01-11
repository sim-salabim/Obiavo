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
            Создать новую группу
        </button>
    </div>

    <?= $breadcrumbs;?>

<?= TableList::widget([
    'title' => 'Сообщества',
    'data'  => $sn_groups,
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
            'label'        => 'Соцсеть',
            'content'      => function($model){
                $html = $model->socialNetwork->name;
                return $html;
            },
        ],
        [
            'label'        => 'основная группа',
            'content'      => function($model){
                $html = ($model->socialNetworksGroupMain) ? $model->socialNetworksGroupMain->name : '<span class="badge badge-warning">Не выбранa</span>';
                return $html;
            },
        ],
        [
            'label'        => 'Город',
            'content'      => function($model){
                $html = (isset($model->city->_text->name)) ? $model->city->_text->name : '<span class="badge badge-warning">Не выбран</span>';
                return $html;
            },
        ],
        [
            'label'        => 'Город',
            'content'      => function($model){
                $html = (isset($model->region->_text->name)) ? $model->region->_text->name : '<span class="badge badge-warning">Не выбран</span>';
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
