<?php
use backend\widgets\TableList;
use yii\helpers\Url;

$this->title = 'Настройки';
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">
    <? if(!$setting){?>
    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['create'])?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Создать настройку
        </button>
    </div>
    <? } ?>
    <? if($setting){?>
        <?= TableList::widget([
            'title' => 'Настройки',
            'data'  => $setting,
            'columns' => [
                [
                    'label'        => 'Общие настройки',
                    'content'      => function($model){
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
    <? } ?>
</div>