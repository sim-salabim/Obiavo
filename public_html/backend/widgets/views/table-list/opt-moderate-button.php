<?php use yii\helpers\Url;?>
<span data-placement="top" data-toggle="tooltip" id="moderate-<?= $model->id ?>" title="Одобрить">
    <button class="btn btn-success btn-xs" onclick="moderate('<?= $model->id ?>')"
            data-link="<?= Url::toRoute(['moderate'])?>">
        <span class="glyphicon glyphicon-ok-sign"></span>
    </button>
</span>
<script>
    function moderate(id){
        $.ajax({
            data: {id:id},
            method: "POST",
            url: "/moderation/moderate/",
            success: function(data){
                var id = data.id;
                $("#moderate-"+id).parent().parent().fadeOut();
            },
            error: function (data) {
                alert('Ошибка модерации');
            }
        });
    }
</script>