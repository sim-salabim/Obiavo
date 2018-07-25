<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $( function() {
        $( "#sortable1, #sortable2" ).sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();
    } );
</script>
<div id="loadcontent-container" style="display: none"></div>
<? if($parent_cat){ ?>
    <?= $this->render('breadcrumbs', ['category' => $parent_cat, 'order' => true]);?>
<? } ?>
<div id="categories-table"  class="box container">
    <div class="box-header">
        <h3 class="box-title">Порядок вывода категорий</h3>
    </div>
    <?php  if(Yii::$app->session->getFlash('message')){ ?>
        <div class="alert alert-success col-12" role="alert">
            <?= Yii::$app->session->getFlash('message'); ?>
        </div>
    <?php  } ?>
    <div class="box-body table-responsive no-padding ">
        <form id="form-order" method="post" action="/categories/save-order">
            <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
                   value="<?=Yii::$app->request->csrfToken?>"/>
            <input type="hidden" name="parent_id" value="<?= $parent_id ?>">
            <div id="sortable1" class="col-lg-12">
                <? foreach($categories as $cat){ ?>
                    <pre style="cursor: move" id="pre-<?= $cat->id ?>"><a href="/categories/order?id=<?= $cat->id ?>"><?= $cat->techname ?></a><input type="hidden" name="category_order[]" value="<?= $cat->id ?>"></pre>
                <? } ?>
                <input type="hidden"  name="reset-order" value="0">
                <input type="checkbox"  name="reset-order" value="1">
                Сбросить порядок
            </div>
            <div class="col-lg-12" style="padding-bottom: 10px;">
                <div class="btn btn-success btn-sm" id="form-submit">
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                    Сохранить
                </div>
            </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var AjaxChatCsrfData = ({
            data: <?= \yii\helpers\Json::encode([
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
        ]) ?>
        });
        $.ajaxSetup(AjaxChatCsrfData);
        $( "#sortable1, #sortable2" ).sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();

        $('#form-submit').bind('click', function () {
            $('#form-order').submit();
        });
    });
</script>