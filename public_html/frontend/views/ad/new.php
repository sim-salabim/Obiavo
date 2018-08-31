<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<div class="<? if(!$user){?> not-authorized-form<? } ?>">
    <? if($user) {?>
    <div class="row">
        <div class="col-12 sub-title">
            <?= __('_Contacts')?>
        </div>
        <div class="col-sm-12 col-md-6">
            <?= __('Your email')?>
        </div>
        <div class="col-sm-12 col-md-6">
            <?= $user->email ?>
        </div>
        <div class="w-100"></div>
        <div class="col-sm-12 col-md-6">
            <?= __('Your name')?>
        </div>
        <div class="col-sm-12 col-md-6">
            <?= $user->getFullName() ?>
        </div>
        <div class="w-100"></div>
        <div class="col-sm-12 col-md-6">
            <?= __('Your phone number')?>
        </div>
        <div class="col-sm-12 col-md-6">
            <?= $user->phone_number ?>
        </div>
    </div>
    <hr>
<? }else{?>
        <div class="col-md-12 alert alert-light no-padding-left" role="alert"><?= __('Please sign in first') ?></div>
        <form class="form-horizontal" method="post" id="login-form">
            <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
                   value="<?=Yii::$app->request->csrfToken?>"/>
            <!-- Email-->
            <div class="form-group validation-errors ">
                <div class="form-group">
                    <input
                        id="email"
                        name="email"
                        <? if(isset($model) AND $model->email){?>
                            value="<?= $model->email ?>"
                        <? }?>
                        placeholder="email@mail.com"
                        class="form-control input-md <?php if(Yii::$app->session->getFlash('email_error')){?> is-invalid<?php }?>" >
                    <?php if(Yii::$app->session->getFlash('email_error')){?>
                        <div class="invalid-feedback">
                            <?= Yii::$app->session->getFlash('email_error') ?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Password-->
            <div class="form-group validation-errors ">
                <div class="form-group">
                    <input id="password" name="password" type="password" placeholder="<?= __('Password') ?>" class="form-control input-md <?php if(Yii::$app->session->getFlash('password_error')){?> is-invalid<?php }?>">
                    <?php if(Yii::$app->session->getFlash('password_error')){?>
                        <div class="invalid-feedback">
                            <?= Yii::$app->session->getFlash('password_error') ?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="form-group">
                <a class="text-info"
                   href="<?= yii\helpers\Url::toRoute('/registration') ?>"
                   style="margin-right: 20px;">
                    <?= __('Registration') ?>
                </a>

                <a class="text-info" href="<?= yii\helpers\Url::toRoute('/recovery') ?>"><?= __('Forgot your password?') ?></a>
            </div>
            <hr>
            <div class="form-group">
                <button class="btn btn-success" data-input="#login-form"><?= __('Sign in') ?></button>

            </div>

        </form>
        <hr>

    <? } ?>
<div class="row padding-bottom-10">
    <div class="col <? if(!$user){?>color-disabled<? } ?> sub-title">
        <?= __('Select category')?>
    </div>
</div>
<?
$selected_category = null;
$selected_category_id = null;
$sub_categories = null;
$selected_sub_category = null;
$sub_sub_categories = null;
$selected_sub_sub_category = null;
$selected_placement_id = null;
$placements = null;
$model = Yii::$app->session->getFlash('model');
$files = [];
if(isset($model) and $user){
    $files = $model->files;
}
?>
<form id="new-ad-form" method="post" enctype="multipart/form-data" action="/publish-add/">
    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           value="<?=Yii::$app->request->csrfToken?>"/>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12" id="checkbox-select">
            <input
                class="form-control bs-autocomplete"
                id="live-cat-search-select"
                value=""
                placeholder="<?= __('Select a category') ?>"
                type="text"
                data-hidden_field_id="hidden-category"
                data-item_id="live-cat-search-select"
                data-item_label="text"
                autocomplete="off">
            <input type="hidden" id="checkbox-tmp" value="0">
            <input type="hidden" id="hidden-category" value="">
            <script type="text/javascript">
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
                                $('#ui-id-1').show();
                                var checkboxTmp = $("#checkbox-tmp").val();
                                $("#hidden-category").val(0);
                                if(checkboxTmp == 1){
                                    $('#checkbox-select').append('<span id="checked-'+ui.item.id+'"><input type="hidden" name="ckecked-categories[]" value="'+ui.item.id+'">'+ui.item.text+' <i style="cursor: pointer" class="fa fa-times" aria-hidden="true" id="checked-close-'+ui.item.id+'" onclick="closeChecked('+ui.item.id+')"></i></span><br>');
                                    var checkedAmount = $("span[id^=checked-]").length;
                                    console.log(checkedAmount);
                                    if(checkedAmount >= 3){
                                        $('#ui-id-1').hide();
                                    }
                                }else{
                                    event.preventDefault();
                                    $('#live-cat-search-select').val(ui.item.text);
                                    $("#hidden-category").val(ui.item.id)
                                    $("span[id*='checked-']").remove();
                                    $('#ui-id-1').hide();
                                }
                                $("#checkbox-tmp").val(0);
                            },
                            close: function( event, ui ) {
                                $('#ui-id-1').show();
                            }
                        }).data('ui-autocomplete')._renderItem = function(ul, item) {
                            return $('<li class="list-group-item" id="li-'+item.id+'"></li>')
                                .data("item.autocomplete", item)
                                .append('<input type="checkbox" id="checkbox-'+item.id+'" onclick="checkBox('+item.id+')" name="ckb"> <a id="a-cat-'+item.id+'" >' + item.text + '</a>')
                                .appendTo(ul);
                        };
                    });
                })();
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
                function closeCheckedAndTree(id){
                    $("#checked-"+id).next().remove();
                    $("#checked-"+id).remove();
                    var node = $("#tree-container").dynatree('getTree').getNodeByKey(""+id+"").select(false);
                }
                $(document).ready(function(){
                    $(window).on("click", function (event) {
                        console.log(event.target.id.indexOf("a-cat-"));
                        if(event.target.id.indexOf("a-cat-") != 0) {
                            if (event.target.id.indexOf("checkbox-") == -1 ) {
                                $("#hidden-category").val('');
                                $("#live-cat-search-select").val('');
                                $('#ui-id-1').hide();
                            }
                        }
                    });
                });
            </script>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <button id="tree-category-select" class="form-control text-align-left" <? if(!$user){?> disabled<? } ?>>
                <?= __('Category tree selection') ?>
            </button>
            <div class="form-control" id="tree-container" style="display:none">
            </div>
        </div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select
                <? if(!$user){?> disabled<? } ?>
                name="placement_id"
                id="action_select"
                class="form-control <?php if(Yii::$app->session->getFlash('placement_id_error')){?> is-invalid<?php }?> <? if(!$user){?>color-disabled<? } ?>">
                <option value="0"><?= __('Action') ?></option>
                <? if($placements and $user){
                    foreach ($placements as $pl){
                        ?>
                        <option value="<?= $pl->id ?>" <? if($pl->id == $selected_placement_id){?>selected<?}?> ><?= $pl->_text->name ?></option>
                    <? }} ?>
            </select>
            <?php if(Yii::$app->session->getFlash('placement_id_error')){?>
                <div class="invalid-feedback">
                    <?= __('Required field') ?>
                </div>
            <?php } ?>
        </div>
        <div class="w-100"></div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select
                <? if(!$user){?> disabled<? } ?>
                name="cities_id"
                class="form-control <?php if(Yii::$app->session->getFlash('cities_id_error')){?> is-invalid<?php }?> <? if(!$user){?>color-disabled<? } ?>"
                disabled>
                <option value="0"><?= __('City') ?></option>
                <? foreach($cities as $city){ ?>
                    <option value="<?= $city->id ?>" <? if($user AND \Yii::$app->user->identity->cities_id == $city->id) {?> selected="true" <? } ?>><?= $city->_text->name ?></option>
                <? } ?>
            </select>
            <?php if(Yii::$app->session->getFlash('cities_id_error')){?>
                <div class="invalid-feedback">
                    <?= __('Required field') ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select
                <? if(!$user){?> disabled<? } ?>
                name="expiry_date"
                class="form-control <?php if(Yii::$app->session->getFlash('expiry_date_error')){?> is-invalid<?php }?> <? if(!$user){?>color-disabled<? } ?>">
                <? if($user){ ?>
                <option value="0" <? if((isset($model) AND $model->expiry_date) AND $model->expiry_date == 0){?>selected<? }?>><?= __('Time') ?></option>
                <option value="86400" <? if((isset($model) AND $model->expiry_date) AND $model->expiry_date == 86400){?>selected<? }?>><?= __('One day') ?></option>
                <option value="172800" <? if((isset($model) AND $model->expiry_date) AND $model->expiry_date == 172800){?>selected<? }?>><?= __('Two days') ?></option>
                <option value="259200" <? if((isset($model) AND $model->expiry_date) AND $model->expiry_date == 259200){?>selected<? }?>><?= __('Three days') ?></option>
                <option value="345600" <? if((isset($model) AND $model->expiry_date) AND $model->expiry_date == 345600){?>selected<? }?>><?= __('Four days') ?></option>
                <option value="432000" <? if((isset($model) AND $model->expiry_date) AND $model->expiry_date == 432000){?>selected<? }?>><?= __('Five days') ?></option>
                <option value="518400" <? if((isset($model) AND $model->expiry_date) AND $model->expiry_date == 518400){?>selected<? }?>><?= __('Six days') ?></option>
                <option value="604800" <? if((isset($model) AND $model->expiry_date) AND $model->expiry_date == 604800){?>selected<? }?>><?= __('One week') ?></option>
                <option value="1209600" <? if((isset($model) AND $model->expiry_date) AND $model->expiry_date == 1209600){?>selected<? }?>><?= __('Two weeks') ?></option>
                <option value="1814400" <? if((isset($model) AND $model->expiry_date) AND $model->expiry_date == 1814400){?>selected<? }?>><?= __('Three weeks') ?></option>
                <option value="2419200" <? if((isset($model) AND $model->expiry_date) AND $model->expiry_date == 2419200){?>selected<? }?>><?= __('One month') ?></option>
                <? } ?>
            </select>
            <?php if(Yii::$app->session->getFlash('expiry_date_error')){?>
                <div class="invalid-feedback">
                    <?= __('Required field') ?>
                </div>
            <?php } ?>
        </div>
        <div class="w-100"></div>
    </div>
    <hr>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <input
                <? if(!$user){?> disabled<? } ?>
                class="form-control <?php if(Yii::$app->session->getFlash('title_error')){?> is-invalid<?php }?> <? if(!$user){?>color-disabled<? } ?>"
                type="text"
                name="title"
                <? if($user AND isset($model) AND $model->title){?>
                    value="<?= $model->title ?>"
                <? }?>
                placeholder="<?= __('Title')?>">
            <?php if(Yii::$app->session->getFlash('title_error')){?>
                    <div class="invalid-feedback">
                        <?= Yii::$app->session->getFlash('title_error') ?>
                    </div>
            <?php } ?>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <textarea
                <? if(!$user){?> disabled<? } ?>
                class="form-control <?php if(Yii::$app->session->getFlash('text_error')){?> is-invalid<?php }?> <? if(!$user){?>color-disabled<? } ?>"
                rows="10"
                name="text"><? if($user AND isset($model) AND $model->text){?><?= $model->text ?><? }?></textarea>
            <?php if(Yii::$app->session->getFlash('text_error')){?>
                <div class="invalid-feedback">
                    <?= Yii::$app->session->getFlash('text_error') ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <input
                <? if(!$user){?> disabled<? } ?>
                class="form-control <?php if(Yii::$app->session->getFlash('price_error')){?> is-invalid<?php }?> <? if(!$user){?>color-disabled<? } ?>"
                type="text"
                name="price"
                <? if($user AND isset($model) AND $model->price){?>
                value="<?= $model->price ?>"
                <? }?>
                placeholder="<?= __('Price')?>">
            <?php if(Yii::$app->session->getFlash('price_error')){?>
                <div class="invalid-feedback">
                    <?= Yii::$app->session->getFlash('price_error') ?>
                </div>
            <?php } ?>
        </div>
        <? if($user){?>
        <div class="form-group col-lg-12 col-sm-12 col-md-12" id="file-uploader" >
            <?=  $this->render('/partials/_file_uploader.php', ['container_id' => 'file-uploader', 'files' => $files]) ?>
        </div>
        <? }?>
    </div>
    <hr>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <button <? if(!$user){?> disabled<? } ?> class="btn btn-success senddata col-lg-2 col-md-6 col-sm-12 <? if(!$user){?>color-disabled-button<? } ?>" data-input="#new-ad-form">
                <?= __('Publish') ?>
            </button>
        </div>
    </div>
</form>
</div>
<script>
    $(document).ready(function() {
        $("#tree-category-select").on("click", function (event) {
            event.preventDefault();
            $("#tree-container").toggle();
        });
        $("#tree-container").dynatree({
            checkbox: true,
            children: [
                <? foreach($categories as $cat){?>
                {title: "<?= $cat->techname ?>", isFolder: true, isLazy: true, key: "<?= $cat->id ?>"},
                <? } ?>
            ],
            onLazyRead: function(dtnode){
                dtnode.appendAjax(
                    {url: "<?= yii\helpers\Url::toRoute('/categories/get-root-categories/') ?>",
                        dataType: "JSON",
                        data: {
                            key: dtnode.data.key,
                            sleep: 1,
                            mode: "branch"
                        }
                    });
            },
            title: "Lazy loading sample",
            onSelect: function(flag, node){
                if(flag){
                    $('#checkbox-select').append('<span id="checked-'+node.data.key+'" class="js_tree_el"><input type="hidden" name="ckecked-categories[]" value="'+node.data.key+'" class="js_tree_el">'+node.data.title+' <i style="cursor: pointer" class="fa fa-times js_tree_el" aria-hidden="true" id="checked-close-'+node.data.key+'" onclick="closeCheckedAndTree('+node.data.key+')"></i></span><br class="js_tree_el">');
                }else{
                    $("#checked-"+node.data.key).next().remove();
                    $("#checked-"+node.data.key).remove();
                }
            },
            debugLevel: 0
        });
    });
</script>

