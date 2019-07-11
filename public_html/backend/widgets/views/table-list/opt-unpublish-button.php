<span data-placement="top" data-toggle="tooltip" id="unpublish-<?= $model->id ?>" title="<? if($model->active){?>Снять с публикации<? }else{?>Опубликовать<? }?>">
    <button id="btn-pbl-<?= $model->id ?>" class="btn <? if ($model->active){?> btn-warning <? }else{?> btn-success <? }?>btn-xs" onclick="<? if ($model->active){?>inactive('<?= $model->id ?>', 'inactivate') <? }else{?>inactive('<?= $model->id ?>', 'activate')<?}?>">
        <span class="glyphicon <? if ($model->active){?> glyphicon-volume-off <? }else{?> glyphicon-volume-up  <?}?>"></span>
    </button>
</span>
<script>
    function inactive(id, url){
        $.ajax({
            data: {id:id},
            method: "POST",
            url: "/moderation/"+url+"/",
            success: function(data){
                var id = data.id;
                if(url === "inactivate"){
                    url = 'activate'
                    $("#btn-pbl-"+id).removeClass('btn-warning');
                    $("#btn-pbl-"+id).addClass('btn-success');
                    $("#btn-pbl-"+id+" span.glyphicon").removeClass('glyphicon-volume-off');
                    $("#btn-pbl-"+id+" span.glyphicon").addClass('glyphicon-volume-up');
                    $("#btn-pbl-"+id).attr('onclick', 'inactive(\''+id+'\', \''+url+'\')');
                    $("#unpublish-"+id).attr('title', 'Опубликовать');
                    $("#btn-pbl-"+id).parent().parent().parent().find('td span.label').removeClass('label-success');
                    $("#btn-pbl-"+id).parent().parent().parent().find('td span.label').addClass('label-default');
                    $("#btn-pbl-"+id).parent().parent().parent().find('td span.label').text('не активно');
                }else{
                    url = 'inactivate'
                    $("#btn-pbl-"+id).removeClass('btn-success');
                    $("#btn-pbl-"+id).addClass('btn-warning');
                    $("#btn-pbl-"+id+" span.glyphicon").removeClass('glyphicon-volume-up');
                    $("#btn-pbl-"+id+" span.glyphicon").addClass('glyphicon-volume-off');
                    $("#btn-pbl-"+id).attr('onclick', 'inactive(\''+id+'\', \''+url+'\')');
                    $("#unpublish-"+id).attr('title', 'Снять с публикации');
                    $("#btn-pbl-"+id).parent().parent().parent().find('td span.label').removeClass('label-default');
                    $("#btn-pbl-"+id).parent().parent().parent().find('td span.label').addClass('label-success');
                    $("#btn-pbl-"+id).parent().parent().parent().find('td span.label').text('активно');
                }
            },
            error: function (data) {
                alert('Возникла ошибка');
            }
        });
    }
</script>