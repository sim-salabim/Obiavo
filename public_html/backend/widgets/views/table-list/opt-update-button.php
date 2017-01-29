<?php use yii\helpers\Url;?>
<span data-placement="top" data-toggle="tooltip" title="Редактировать">
    <button class="btn btn-primary btn-xs loadcontent"
            data-link="<?= Url::toRoute(['update','id' => $model->id])?>">
        <span class="glyphicon glyphicon-pencil"></span>
    </button>
</span>