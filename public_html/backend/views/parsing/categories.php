<?php
use backend\widgets\TableList;
use yii\helpers\Url;

$this->title = 'Парсинг категорий';
?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">Парсинг падежей категорий</h3>

        <div class="box-tools">
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive">
        <button class="btn btn-primary categories-parsing">запустить</button>
    </div>

    <p><k id="progress-amount">0</k>/<k id="amount"><?= $categories_amount ?></k></p><br>
    <div class="progress">
        <div class="progress-bar"  id="bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
<script>
    $(document).ready(function(){
        var limit = 50;
        var offset = 0;
        var parsed = 0;
        var allAmount = $("#amount").text();
        $(".categories-parsing").bind("click", function(){
            parse(offset, limit, allAmount, parsed);
        });
    });

    function parse(offset, limit, allAmount, parsed){
        $.ajax({
            dataType: "json",
            type : 'POST',
            url: '<?= \yii\helpers\Url::toRoute('parsing/categories-parsing') ?>',
            data: {limit: limit, offset: offset, amount: allAmount, parsed: parsed},
            success: function(data) {
                console.log(data);
                if(data.parsed < allAmount){
                    $("#progress-amount").text(data.parsed);
                    $("#bar").attr("style", "width: "+data.persantage+"%");
                    parse(data.parsed, limit, allAmount, data.parsed);
                }
            }
        });
    }
</script>