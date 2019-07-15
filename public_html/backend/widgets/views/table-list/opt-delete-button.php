<?php use yii\helpers\Url;?>
<? $id = uniqid();?>
<span data-placement="top" data-toggle="tooltip" title="Удалить">
    <button id="<?= $id ?>" class="btn btn-danger btn-xs "
            data-link="<?= Url::toRoute(['delete','id' => $model->id])?>">
        <span class="glyphicon glyphicon-trash"></span>
    </button>
</span>
<script>
    $(document).ready(function(){
        $('#<?= $id?>').bind('click', function(e){
            if(!$(this).hasClass('senddata')) {
                var c = confirm('Удалить?');
                if (c) {
                    $(this).addClass('senddata');
                    $(this).click();
                }
            }
        });
    });
</script>