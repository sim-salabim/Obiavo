<?php use yii\helpers\Url;?>
<span data-placement="top" data-toggle="tooltip" title="Удалить">
    <button class="btn btn-danger btn-xs senddata"
            onclick="alert('Удалить?');"
            data-link="<?= Url::toRoute(['delete','id' => $model->id])?>">
        <span class="glyphicon glyphicon-trash"></span>
    </button>
</span>