<?
/**
 *  $attribute - array(label, name, model, type, url, placeholder, input_id)
 *      label - название поля;
 *      current_values - [[id => , title => ]], массив с текущими значениями
 *      name - название инпута (name="name");
 *      model - класс модели;
 *      type - Form::SEARCH_AUTOCOMPLETE;
 *      url - урл для ajax поиска;
 *      input_id - id, который присвоиться инпуту, если не предоставлен, то притсваивается 'input-.uniqid()'
 *      placeholder;
 */
$id = (isset($attribute['input_id'])) ? $attribute['input_id'] : 'input-'.uniqid();
$model_name = $attribute['model_name'];
$values = (isset($attribute['current_values']) OR !empty($attribute['current_values'])) ? $attribute['current_values'] : [];
$placeholder = (isset($attribute['placeholder'])) ? $attribute['placeholder'] : 'Начните печатать...'
?>
<div class="form-group row validation-errors">
    <label class="col-xs-2 col-form-label"><?= $attribute['label'] ?></label>
    <div class="col-xs-10">
        <input
            class="form-control bs-autocomplete-<?= $id ?>"
            id="<?= $id ?>"
            value=""
            type="text"
            placeholder="<?= $placeholder ?>"
            data-hidden_field_id="hidden-<?= $id ?>"
            data-item_id="<?= $id ?>"
            data-item_label="text"
            autocomplete="off">
        <div class="div-container-<?= $id ?>">
            <? foreach($values as $v){ ?>
            <span class="badge badge-primary" id="selected-<?= $id ?>">
                <?= $v['title'] ?>
                <i class="fa fa-times cursor-pointer selected-item" onclick="$(this).parent().remove()" aria-hidden="true">
                    <input type="hidden" name="<?= $attribute['name'] ?>[]" value="<?= $v['id'] ?>">
                </i>
            </span>
            <? } ?>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#<?= $id ?>').on('keyup', function(){
            var visible_val = $('#<?= $id ?>').val();
            var hidden_val = $('#hidden-<?= $id ?>').val();
            if(visible_val != hidden_val){
                $('#hidden-<?= $id ?>').val('');
            }
        });
    });
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
        $('.bs-autocomplete-<?= $id ?>').each(function() {
            var _this = $(this),
                _data = _this.data(),
                _search_data = [],
                _div_container = $('.div-container-<?= $id ?>')


            _this.after('<div class="bs-autocomplete-feedback form-control-feedback"><div class="loader"><?= __('Поиск...') ?></div></div>')
                .parent('.form-group').addClass('has-feedback');

            var feedback_icon = _this.next('.bs-autocomplete-feedback');
            feedback_icon.hide();

            _this.autocomplete({
                minLength: 3,
                autoFocus: true,

                source: function(request, response) {
//                    _hidden_field.val('');
                    $.ajax({
                        dataType: "json",
                        type : 'POST',
                        url: '<?= \yii\helpers\Url::toRoute($attribute['url']) ?>',
                        data: {query: $('#'+'<?= $id ?>').val()},
                        success: function(data) {
                            _search_data = data;
                            $('input.suggest-user').removeClass('ui-autocomplete-loading');

                            response(data);
                        }
                    });
                },

                search: function() {
                    feedback_icon.show();

                },

                response: function() {
                    feedback_icon.hide();
                },

                focus: function(event, ui) {
                    event.preventDefault();
                },

                select: function(event, ui) {
                    var selected= 'opa';
                    _div_container.append('<span class="badge badge-primary ka" >'+ui.item.text+' <i class="fa fa-times cursor-pointer selected-item" aria-hidden="true" onclick="$(this).parent().remove()"><input type="hidden" name="<?= $attribute['name'] ?>[]" value="'+ui.item.id+'"></i></span>')
                },
                close: function( event, ui ) {

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