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
<?= $breadcrumbs;?>
<div id="categories-table"  class="box container">
    <div class="box-header">
        <h3 class="box-title">Порядок вывода городов</h3>
    </div>
    <div class="box-body table-responsive no-padding ">
        <div class="ui-widget from-group ">
            <input
                style="margin-left: 15px; width: 97%"
                class="form-control bs-autocomplete"
                id="live-search-select"
                value=""
                placeholder="<?= 'Выберите город' ?>"
                type="text"
                data-hidden_field_id="hidden-city"
                data-item_id="live-search-select"
                data-item_label="text"
                autocomplete="off">
            <hr>
        </div>
        <form id="form-order" method="post" action="/cities/save-order">
            <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
                   value="<?=Yii::$app->request->csrfToken?>"/>
            <input type="hidden" name="country_id" value="<?= $country_id ?>">
        <div id="sortable1" class="col-lg-12">
                <? foreach($cities as $city){ ?>
                    <pre style="cursor: move" id="pre-<?= $city->id ?>"><?= $city->_text->name ?><input type="hidden" name="city_order[]" value="<?= $city->cities_id ?>"><i class="fa fa-window-close close-icon"  aria-hidden="true" onclick="removeOrder('<?= $city->id ?>')"></i></pre>
                <? } ?>
        </div>
        <div class="col-lg-12" style="padding-bottom: 10px;">
            <div class="btn btn-success btn-sm" id="form-submit">
                <span class="glyphicon glyphicon-floppy-disk"></span>
                Сохранить
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $.widget("ui.autocomplete", $.ui.autocomplete, {

        _renderMenu: function(ul, items) {
            var that = this;
            ul.attr("class", "nav nav-pills nav-stacked  bs-autocomplete-menu list-group");
            $.each(items, function(index, item) {
                that._renderItemData(ul, item);
            });
        },

        _resizeMenu: function() {
            var ul = this.menu.element;
            ul.outerWidth(Math.min(
                ul.width("").outerWidth() + 1,
                this.element.outerWidth()
            ));
        }

    });

    (function() {
        $('.bs-autocomplete').each(function() {
            var _this = $(this),
                _data = _this.data(),
                _search_data = [],
                _visible_field = $('#' + _data.item_id),
                _hidden_field = $('#' + _data.hidden_field_id);


            _this.after('<div class="bs-autocomplete-feedback form-control-feedback"><div class="loader"><?= __('Поиск...') ?></div></div>')
                .parent('.form-group').addClass('has-feedback');

            var feedback_icon = _this.next('.bs-autocomplete-feedback');
            feedback_icon.hide();

            _this.autocomplete({
                minLength: 3,
                autoFocus: true,

                source: function(request, response) {
                    _hidden_field.val('');
                    $.ajax({
                        dataType: "json",
                        type : 'POST',
                        url: '<?= \yii\helpers\Url::toRoute('cities/search') ?>',
                        data: {query: $('input#live-search-select').val(),country_id:<?= $country_id ?>},
                        success: function(data) {
                            _search_data = data
                            $('input.suggest-user').removeClass('ui-autocomplete-loading');
                            if(_search_data.length == 0){
                                _hidden_field.val('');
                            }
                            response(data);
                        }
                    });
                },

                search: function() {
                    feedback_icon.show();
                    _hidden_field.val('');
                },

                response: function() {
                    feedback_icon.hide();
                },

                focus: function(event, ui) {
                   // _this.val(ui.item[_data.item_label]);
                    event.preventDefault();
                },

                select: function(event, ui) {
                    $('#sortable1').prepend('<pre style="cursor: move" id="pre-'+ui.item.id+'">'+ui.item.text+'<input type="hidden" name="city_order[]" value="'+ui.item.id+'"><i class="fa fa-window-close close-icon"  aria-hidden="true" onclick="removeOrder('+ui.item.id+')"></i></pre>')
                },
                close: function( event, ui ) {
                    if(_search_data.length != 0){
                        _hidden_field.val(_search_data[_visible_field.val()].id);
                    }
                }
            })
                .data('ui-autocomplete')._renderItem = function(ul, item) {
                return $('<li class="list-group-item" ></li>')
                    .data("item.autocomplete", item)
                    .append('<a>' + item[_data.item_label] + '</a>')
                    .appendTo(ul);
            };
        });
    })();
</script>
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
    function removeOrder(id){
        $.ajax({
            method: "POST",
            data: {id:id},
            url:'<?= \yii\helpers\Url::toRoute('cities/remove-order') ?>',
            success: function(){
                $('#pre-'+id).remove();
            }
        });
    }
</script>