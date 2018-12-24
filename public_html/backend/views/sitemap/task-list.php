<?php
Use yii\helpers\Url;
use backend\widgets\TableList;
use yii\helpers\Html;
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">

    <?= TableList::widget([
        'title' => 'Sitemap задачи',
        'data'  => $tasks,
        'columns' => [
            [
                'attribute'    => 'id',
                'label'        => 'ID',
            ],
            [
                'label'        => 'Страна',
                'content'      => function($model){
                    $html = $model->country->_text->name;
                    return $html;
                },
            ],
            [
                'label'        => 'Статус',
                'content'      => function($model){
                    $html = $model->status;
                    if($model->status == \common\models\SitemapTasks::PROCESSING_STATUS){
                        $html .= "<br>".$model->persentage."%";
                    }
                    return $html;
                },
            ],
            [
                'label'        => 'Создана',
                'content'      => function($model){
                    $html = $model->created_at;
                    return $html;
                },
            ],
        ]
    ]);?>

</div>