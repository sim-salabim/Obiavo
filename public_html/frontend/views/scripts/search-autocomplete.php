<script type="text/javascript">
    var categoriesLimit = '<?= $categories_limit ?>'
    $.widget("ui.autocomplete", $.ui.autocomplete, {

        _renderMenu: function(ul, items) {
            var that = this;
            ul.attr("class", "nav nav-pills nav-stacked  bs-autocomplete-menu list-group flex-wrap-none");
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
                _hidden_field = $('#' + _data.hidden_field_id);


            _this.after('<div class="bs-autocomplete-feedback form-control-feedback"><div class="loader"><?= __('Search...') ?></div></div>')
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
                        url: '<?= \yii\helpers\Url::toRoute('categories/search-categories-for-select') ?>',
                        data: {q: $('input#live-cat-search-select').val()},
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
                    event.preventDefault();
                },
                select: function(event, ui) {
                    var checkedAmount = $("span[id^=checked-]").length;
                    var checkboxInputVal = $('#checkbox-'+ui.item.id+':checked');
                    if(categoriesLimit > checkedAmount) {
                        // $('#ui-id-1').show();
                        var checkboxTmp = $("#checkbox-tmp").val();
                        $("#hidden-category").val(0);
//                                    if (checkboxTmp == 1) {
                        var choosen = $("#checked-"+ui.item.id);
                        if(choosen.length == 0) {
                            $('#checkbox-select').append('<span id="checked-' + ui.item.id + '"><input type="hidden" name="categories[]" class="from-select-' + ui.item.id + '" value="' + ui.item.id + '">' + ui.item.text + ' <i style="cursor: pointer" class="fa fa-times" aria-hidden="true" id="checked-close-' + ui.item.id + '" onclick="closeChecked(' + ui.item.id + ')"></i></span><br>');
                            selectNode(ui.item.id);

                            if (categoriesLimit <= checkedAmount) {
                                $('#ui-id-1').hide();
                            }
//                                    } else {
//                                        event.preventDefault();
//                                        $('#live-cat-search-select').val(ui.item.text);
//                                        $("#hidden-category").val(ui.item.id)
//                                        $("span[id*='checked-']").next().remove()
//                                        $("span[id*='checked-']").remove();
//                                        $('#ui-id-1').hide();
//                                    }
                            $("#checkbox-tmp").val(0);
                        }else{
                            closeCheckedAndTree(ui.item.id);
                        }
                    }else if(categoriesLimit <= checkedAmount && checkboxInputVal.length > 0){
                        alert("<?= __('Categories limit:') ?> " + categoriesLimit);
                        $("input[id^=checkbox-]").remove();
                    }
                },
                close: function( event, ui ) {
                    $('#ui-id-1').show();
                }
            }).data('ui-autocomplete')._renderItem = function(ul, item) {
                var checkedAmount = $("span[id^=checked-]").length;
                var string = "";
                if(categoriesLimit <= checkedAmount){
                    string = item.text ;
                }else{
                    var choosen = $("#checked-"+item.id);
                    if(choosen.length > 0){
                        string  = '<input type="checkbox" checked id="checkbox-'+item.id+'" onclick="checkBox('+item.id+')" name="ckb"> <a id="a-cat-'+item.id+'" onclick="hideSearchContainer('+item.id+')">' + item.text + '</a>';
                    }else{
                        string  = '<input type="checkbox" id="checkbox-'+item.id+'" onclick="checkBox('+item.id+')" name="ckb"> <a id="a-cat-'+item.id+'" onclick="hideSearchContainer('+item.id+')">' + item.text + '</a>';
                    }
                }
                return $('<li class="list-group-item" id="li-'+item.id+'"></li>')
                    .data("item.autocomplete", item)
                    .append(string)
                    .appendTo(ul);
            };
        });
    })();
    function hideSearchContainer(id){
        $("#checkbox-"+id).prop('checked');
        $("#ui-id-1").hide();
    }
    function checkBox(id){
        var checked = $('#checkbox-'+id).prop( "checked" );
        if(checked){
            $("#checkbox-tmp").val(1);
        }else{
            $("#checkbox-tmp").val(0);
        }
    }
    function closeChecked(id){
        $("#checked-"+id).next().remove();
        $("#checked-"+id).remove();
    }
    $(document).ready(function(){
        $(window).on("click", function (event) {
            if(event.target.id.indexOf("a-cat-") != 0) {
                if (event.target.id.indexOf("checkbox-") == -1 ) {
                    $("#hidden-category").val('');
                    $("#live-cat-search-select").val('');
                    $('#ui-id-1').hide();
//                                $('#tree-container').hide();
                }
            }
        });
    });
</script>