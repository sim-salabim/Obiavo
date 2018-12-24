<?php
Use yii\helpers\Url;
use backend\widgets\TableList;
use yii\helpers\Html;
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">


<?
foreach($tasks as$task){ ?>

    <div class="col-lg-10">
        <?= $task->country->_text->name ?>
    </div>
    <div class="col-lg-2">
        <button class="btn btn-primary" onclick="startTask('<?= $task->id ?>')">Начать</button>
    </div>
    <div class="col-lg-12" id="progress-bar"></div>
<? }
?>

</div>
<script>
    // $(document).ready(function(){
        function startTask(id){
            $.ajax({
                type: "POST",
                url: "<?= Url::toRoute(['sitemap-cron/index'])?>",
                data: id,
                dataType: 'json',
                success: function (data) {
                    //data = JSON.parse(data);
                    console.log(data[0]);
                    if(data[0] == "success"){
                        startTask(id);
                    }else{
                        alert("finished");
                    }
                }
                });
        }
    // });
</script>