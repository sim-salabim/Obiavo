<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
Use yii\helpers\Url;
use backend\widgets\TableList;

$breadcrumbs = '';
$sort = \Yii::$app->request->get('sort');
$dir = \Yii::$app->request->get('dir');
$main_group_label  = '<a id="main_group_sorting" href="?sort=main_group&dir=DESC">Основная группа</a>';
if($sort and $dir){
    if($sort == 'main_group' and $dir == 'DESC'){
        $main_group_label  = '<a id="main_group_sorting" href="?sort=main_group&dir=ASC">Основная группа</a> <i class="fa fa-caret-down" aria-hidden="true"></i>';
    }
    if($sort == 'main_group' and $dir == 'ASC'){
        $main_group_label  = '<a id="main_group_sorting" href="?sort=main_group&dir=DESC">Основная группа</a> <i class="fa fa-caret-up" aria-hidden="true"></i>';
    }
}

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
            'label'        => $main_group_label,
            'content'      => function($model){
                $html = ($model->socialNetworksGroupMain) ? $model->socialNetworksGroupMain->name : '<span class="badge badge-warning">Не выбранa</span>';
                return $html;
            },
        ],

        [
            'label'        => 'Страна',
            'content'      => function($model){
                $html = (isset($model->country->_text->name)) ? $model->country->_text->name : '<span class="badge badge-warning">Не выбрана</span>';
                return $html;
            },
        ],
        [
            'label'        => 'Регион',
            'content'      => function($model){
                $html = (isset($model->region->_text->name)) ? $model->region->_text->name : '<span class="badge badge-warning">Не выбран</span>';
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
