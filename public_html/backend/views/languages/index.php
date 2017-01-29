<?php
use yii\helpers\Url;
use backend\widgets\TableList;
use yii\helpers\Html;
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="lang-table">


    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['append'])?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Создать новый пункт
        </button>
    </div>


<?= TableList::widget([
    'title' => 'Языки',
    'data'  => $languages,
    'columns' => [
        [
            'attribute'    => 'id',
            'label'        => 'ID',
        ],
        [
            'label'        => 'Название',
            'content'      => function($model){
                $html = $model->_text->name;
                $html .= backend\helpers\ActiveLabel::status($model->active, [
                        'active' => 'используется',
                        'inactive' => 'не используется'
                        ]);

                return $html;
            },
        ],
        [
            'attribute'     => 'code',
            'label'        => 'Код языка',
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