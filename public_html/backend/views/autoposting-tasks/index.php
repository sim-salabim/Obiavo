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
            Создать новую задачу
        </button>
    </div>

    <?= $breadcrumbs;?>

    <?= TableList::widget([
        'title' => 'Задачи автопостинга',
        'data'  => $tasks,
        'columns' => [
            [
                'attribute'    => 'id',
                'label'        => 'ID',
            ],
            [
                'label'        => 'Обьявление',
                'content'      => function($model){
                    $url = $model->ad->city->region->country->domain.'/'.$model->ad->url.'/';
                    $html = '<a href="http://'.$url.'">'.$model->ad->title.'</a>';
                    return $html;
                },
            ],
            [
                'label'        => 'Соцсеть',
                'content'      => function($model){
                    $html = $model->socialNetworksGroup->socialNetwork->name;
                    return $html;
                },
            ],
            [
                'label'        => 'Соцгруппа',
                'content'      => function($model){
                    $html = $model->socialNetworksGroup->name;
                    return $html;
                },
            ],
            [
                'label'        => 'Статус',
                'content'      => function($model){
                    $html = '';
                    if($model->status == \common\models\AutopostingTasks::STATUS_PENDING) $html .= '<span class="btn btn-warning btn-xs">Ожидание</span>';
                    if($model->status == \common\models\AutopostingTasks::STATUS_FAILED) $html .= '<span class="btn btn-danger btn-xs">Ошибка</span>';
                    if($model->status == \common\models\AutopostingTasks::STATUS_POSTED) $html .= '<span class="btn btn-success btn-xs">Выполнено</span>';
                    if($model->status == \common\models\AutopostingTasks::STATUS_IN_PROGRESS) $html .= '<span class="btn btn-light btn-xs">Выполняется</span>';
                    return $html;
                },
            ],
            [
                'label'        => 'Приоритет',
                'content'      => function($model){
                    $html = ($model->priority) ? '<span class="btn btn-success btn-xs">Активно</span>' : '<span class="btn btn-danger btn-xs">Неактивно</span>';
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
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?
            $index = 1;
            while($index <= $pages_amount){?>
                <li class="page-item <? if($current_page == $index){?>disabled<? } ?>"><a class="page-link" href="<?= "?page=".$index ?>"><?= $index ?></a></li>
                <?
                $index++;
            } ?>
        </ul>
    </nav>
</div>
