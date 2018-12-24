<?php use yii\helpers\Url;?>
<span data-placement="top" data-toggle="tooltip" title="Запустить">
    <button class="btn btn-success btn-xs senddata"
            data-link="<?= Url::toRoute(['run','id' => $model->id])?>">
        <span class="glyphicon glyphicon-repeat"></span>
    </button>
</span>