<?php
Use yii\helpers\Url;
use backend\widgets\TableList;
use yii\helpers\Html;
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">


    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['create'])?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Добавить пользователя
        </button>
    </div>

<?= TableList::widget([
    'title' => '',
    'data'  => $users,
    'columns' => [
        [
            'attribute'    => 'id',
            'label'        => 'ID',
        ],
        [
            'attribute'    => 'email',
            'label'        => 'Email',
        ],
        [
            'attribute'    => 'fullname',
            'label'        => 'ФИО',
        ],
        [
            'attribute'    => 'city._text.name',
            'label'        => 'город',
        ],
    ]
]);?>

</div>