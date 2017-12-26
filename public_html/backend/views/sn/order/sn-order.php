
<script>
    $( function() {
        $( "#sortable1" ).sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();
    } );
</script>
<?= $breadcrumbs;?>
<div id="categories-table"  class="box container">
    <div class="box-header">
        <h3 class="box-title">Порядок вывода блоков</h3>
    </div>
    <div class="box-body table-responsive no-padding ">
        <form id="form-order" method="post" action="/sn/save-order">
            <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
                   value="<?=Yii::$app->request->csrfToken?>"/>
            <div id="sortable1" class="col-lg-12">
                    <? foreach($sns as $sn){ ?>
                        <pre style="cursor: move" id="pre-<?= $sn->id ?>"><?= $sn->name ?><input type="hidden" name="sn_order[]" value="<?= $sn->id ?>"></pre>
                    <? } ?>
            </div>
            <div class="col-lg-12" style="padding-bottom: 10px;">
                <div class="btn btn-success btn-sm" id="form-submit">
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                    Сохранить
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#loadcontent-container').show();
        var AjaxChatCsrfData = ({
            data: <?= \yii\helpers\Json::encode([
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
        ]) ?>
        });
        $.ajaxSetup(AjaxChatCsrfData);
        $( "#sortable1" ).sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();

        $('#form-submit').bind('click', function () {
            $('#form-order').submit();
        });
    });
</script>