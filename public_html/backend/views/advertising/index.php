<?php

use backend\widgets\TableList;
use yii\helpers\Url;

?>

<div id="loadcontent-container" style="display: none"></div>

<div id="lang-table">


    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['create'])?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Создать новый пункт
        </button>
    </div>


    <?= TableList::widget([
        'title' => 'Реклама',
        'data'  => $advertising,
        'columns' => [
            [
                'attribute'    => 'id',
                'label'        => 'ID',
            ],
            [
                'attribute'     => 'name',
                'label'        => 'Название',
            ],
            [
                'attribute'     => 'placement',
                'label'        => 'Расположение',
            ],
            [
                'label'        => 'Активность',
                'content'      => function($advertising){
                    $html = backend\helpers\ActiveLabel::status($advertising->active, [
                        'active' => 'активно',
                        'inactive' => 'не активно'
                    ]);

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