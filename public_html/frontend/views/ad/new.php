<? require_once 'dynatree_script.php'?>
<?
$url = \yii\helpers\Url::toRoute('cities/search-cities-for-select');
$selectCity = __('Select a city');
?>
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
        <nav class="padding-bottom-10">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-main-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Без регистрации</a>
                <a class="nav-item nav-link" id="nav-login-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Авторизация</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-login-tab">
                <form class="form-horizontal" method="post" id="login-form">
                    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
                           value="<?=Yii::$app->request->csrfToken?>"/>
                    <!-- Email-->
                    <div class="form-group validation-errors ">
                        <div class="form-group">
                            <input
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

                </form></div>
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-main-tab">
                <div class="row padding-bottom-10">
                    <div class="col sub-title">
                        <?= __('Contact info') ?>
                    </div>
                </div>
                <form class="form-horizontal" method="post" id="login-form">
                    <div class="form-group validation-errors ">
                        <div class="form-group">
                            <input
                                    id="email"
                                    name="email"
                                    placeholder="E-mail"
                                    class="form-control input-md " >
                                <div class="invalid-feedback" id="email_error"></div>
                        </div>
                    </div>
                    <div class="form-group validation-errors ">
                        <div class="form-group">
                            <input
                                    id="name"
                                    name="name"
                                    placeholder="Ваше имя"
                                    class="form-control input-md " >
                                <div class="invalid-feedback" id="name_error"></div>
                        </div>
                    </div>
                    <div class="form-group validation-errors ">
                        <div class="form-group">
                            <input
                                    id="phone"
                                    name="phone"
                                    placeholder="Номер телефона"
                                    class="form-control input-md " >
                                <div class="invalid-feedback" id="phone_error"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<!--        <div class="col-md-12 alert alert-light no-padding-left" role="alert">--><?//= __('Please sign in first') ?><!--</div>-->
        <hr>

    <? } ?>

<?
$selected_categories = [];
$model = Yii::$app->session->getFlash('model');
$files = [];
$if_user_logged = ($user) ? 1 : 0;
if(isset($model) and $user){
    $files = $model->files;
}
?>
<form id="new-ad-form" method="post" enctype="multipart/form-data" action="/publish-add/">
    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           value="<?=Yii::$app->request->csrfToken?>"/>
    <div class="row">
<!--        <div class="form-group col-lg-12 col-sm-12 col-md-12" >-->
<!--            <input-->
<!--                --><?// if(!$user){?><!-- disabled--><?// } ?>
<!--                class="form-control bs-autocomplete --><?// if(!$user){?><!-- color-disabled--><?// } ?><!--  --><?php //if(Yii::$app->session->getFlash('categories_error')){?><!-- is-invalid --><?// } ?><!--"-->
<!--                id="live-cat-search-select"-->
<!--                value=""-->
<!--                placeholder="--><?//= __('Select a category') ?><!--"-->
<!--                type="text"-->
<!--                data-hidden_field_id="hidden-category"-->
<!--                data-item_id="live-cat-search-select"-->
<!--                data-item_label="text"-->
<!--                autocomplete="off">-->
<!--            <input type="hidden" id="checkbox-tmp" value="0">-->
<!--            <input type="hidden" id="hidden-category" value="">-->
<!--           --><?//= $this->render('/scripts/search-autocomplete', ['categories_limit' => $categories_limit]); ?>
<!--        </div>-->
        <div class="form-group col-lg-12 col-sm-12 col-md-12" id="checkbox-select">
            <div class="row">
                <div id="sub-title" class="col sub-title padding-bottom-0">
                    <?= __('Select category')?>
                </div>
            </div>
                <?

                echo __('Pick a category. The category firstly picked wil be the main one for the ad.')." ".__("You can pick free only")." ".countString(\common\models\Settings::find()->one()->categories_limit, [__("pick_one_category"), __("pick_two_category"),__("pick_more_category")]).". <a href='/help-obiavlenya/' id='help-obiavlenia' target='_blank' >".__("Get details about posting ads?")."</a>";
             ?>
            <button id="tree-category-select" class="form-control text-align-left cursor-pointer margin-top-15 <?php if(Yii::$app->session->getFlash('categories_error')){?> is-invalid <? } ?>" >
                <?= __('Category tree selection') ?>
            </button>
                <div class="invalid-feedback" id="categories_error"></div>
            <div class="form-control" id="tree-container" style="">
            </div>
        </div>
        </div>
        <hr class="width-100">
        <div class="col-12 sub-title padding-left0  " id="picked-cats-div" ><?= __('Picked categories') ?></div>
        <div id="category-append">
            <p id="no-cats-picked"><?= __('No categories picked') ?></p>
        </div>
        <hr class="width-100">
        <?= $this->render('/scripts/tree-select', ['categories' => $categories, 'categories_limit' => $categories_limit, "if_user_logged" => $if_user_logged,
         "selected_categories" => $selected_categories]); ?>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <select
                name="placement_id"
                id="placement_id"
                class="form-control <?php if(Yii::$app->session->getFlash('placement_id_error')){?> is-invalid<?php }?> ">
                <option value="0"><?= __('Action') ?></option>
                <? if($placements){
                    foreach ($placements as $pl){
                        ?>
                        <option value="<?= $pl->id ?>" <? if(isset($model) and $model AND $pl->id == $model->placement_id){?>selected<?}?> ><?= $pl->_text->name ?></option>
                    <? }} ?>
            </select>
            <div class="invalid-feedback" id="placement_id_error"></div>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12 margin-bottom0">
            <? if($user){?>
                <select
                    name="cities_id"
                    id='cities_id'
                    disabled
                    class="form-control <?php if(Yii::$app->session->getFlash('cities_id_error')){?> is-invalid<?php }?>">
                    <option value="0"><?= __('City') ?></option>
                </select>
            <? }else{?>
                <div class="form-group validation-errors">
                    <div class="form-group">
                        <input
                                class="form-control bs-autocomplete <?php if(Yii::$app->session->getFlash('cities_id_error')){?> is-invalid<?php }?>"
                                id="live-search-select"

                                placeholder="<?= $selectCity ?>"
                                type="text"
                                data-hidden_field_id="cities_id"
                                data-item_id="live-search-select"
                                data-item_label="text"
                                autocomplete="off">
                            <div class="invalid-feedback" id="cities_id_error"></div>
                        <input type="hidden" id="cities_id" name="cities_id" <? if(isset($model) AND $model->cities_id){?>
                            value="<?= $model->cities_id ?>"
                        <? }else{?> value=""<? } ?>>
                    </div>
                </div>
            <? } ?>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <select
                name="expiry_date"
                id="expiry_date"
                class="form-control ">
                <option value="0"><?= __('Pick time range') ?></option>
                <option value="<?= \common\models\Ads::DATE_RANGE_ONE_MONTH ?>"><?= __('One month') ?></option>
                <option value="<?= \common\models\Ads::DATE_RANGE_THREE_MONTHS ?>"><?= __('Three months') ?></option>
                <option value="<?= \common\models\Ads::DATE_RANGE_SIX_MONTHS ?>"><?= __('Six months') ?></option>
                <option value="<?= \common\models\Ads::DATE_RANGE_ONE_YEAR ?>"><?= __('One year') ?></option>
                <option value="<?= \common\models\Ads::DATE_RANGE_TWO_YEARS ?>"><?= __('Two years') ?></option>
                <option value="<?= \common\models\Ads::DATE_RANGE_THREE_YEARS ?>"><?= __('Three years') ?></option>
                <option value="<?= \common\models\Ads::DATE_RANGE_UNLIMITED ?>"><?= __('Unlimited') ?></option>
            </select>
            <div class="invalid-feedback" id="expiry_date_error"></div>
        </div>
    </div>
    <hr class="margin-top-0">
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <input
                class="form-control <?php if(Yii::$app->session->getFlash('title_error')){?> is-invalid<?php }?> "
                type="text"
                name="title"
                id="title"
                <? if($user AND isset($model) AND $model->title){?>
                    value="<?= $model->title ?>"
                <? }?>
                placeholder="<?= __('Title')?>">
                    <div class="invalid-feedback" id="title_error"></div>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <textarea
                class="form-control <?php if(Yii::$app->session->getFlash('text_error')){?> is-invalid<?php }?> "
                rows="10"
                placeholder="<?= __('Write your ad\'s text') ?>"
                name="text"
                id="text"
            ><? if($user AND isset($model) AND $model->text){?><?= $model->text ?><? }?></textarea>
            <div class="invalid-feedback" id="text_error"></div>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <input
                class="form-control <?php if(Yii::$app->session->getFlash('price_error')){?> is-invalid<?php }?> "
                type="text"
                name="price"
                id="price"
                <? if($user AND isset($model) AND $model->price){?>
                value="<?= $model->price ?>"
                <? }?>
                placeholder="<?= __('Price')?>">
            <div class="invalid-feedback" id="price_error"></div>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12 " id="dropzone-container" >
            <div class="dropzone" id="file-uploader"></div>
        </div>
        <?=  $this->render('/partials/_file_uploader.php', ['container_id' => 'file-uploader', 'files' => $files]) ?>
    </div>
    <div class="form-group validation-errors">
        <label id="agreement-label">
            <input
                    id="agreement"
                    name="agreement"
                    type="checkbox"> <?= __('Publishing you\'re accepting') ?> <a id="agreement-link" href="/polzovatelskoe-soglashenie/" target="_blank"><?= __('User agreement')?></a> <?= __('and agree with') ?> <a id="policy-link" href="/policy/" target="_blank"><?= __('Privacy policy') ?></a>.</label>
    </div>
        <div class="invalid-feedback dispaly-block" id="agreement_error"></div>
    <hr>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <button
                class="btn btn-success senddata col-lg-2 col-md-6 col-sm-12 publish-button"
            id="publication-button"
            >
                <?= __('Publish') ?>
            </button>
        </div>
    </div>
</form>
</div>
<script>
    $(document).ready(function(){
        $('#publication-button').bind('click', function(e){
            e.preventDefault();
            $("[id*='_error']").text('');
            $(".is-invalid").removeClass('is-invalid');
            //TODO написать экшен, который будет сразу валидировать и сохранять если все ОК
            var user = false;
            <? if($user) {?>
                user = true;
            <? } ?>
            var data = new Object();
            if(user === false){
                data.email = $('#email').val();
                data.name = $('#name').val();
                data.phone = $('#phone').val();
            }
            data.agreement = null;
            if($('#agreement').is(":checked")){
                data.agreement = 1;
            }
            data.categories = [];
            $('input[name*=categories]').each(function( index ) {
                data.categories[index] = $(this).val();
            });
            data.files = [];
            $('input[name*=files]').each(function(index){
                data.files[index] = $(this).val();
            });
            data.placement_id = $('#placement_id :selected').val();
            data.cities_id = $('#cities_id').val();
            data.title = $('#title').val();
            data.text = $('#text').val();
            data.price = $('#price').val();
            console.log(data);
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "/apply-add/",
                data: data,
                success:function(data){
                    // если все огонь
                    if(data.message == '<?= \frontend\models\NewAdForm::MESSAGE_SUCCESS?>'){
                        window.location.replace(window.location.origin+"/"+data.url+"/");
                    }
                    //если НЕ все огонь
                    if(data.message == '<?= \frontend\models\NewAdForm::MESSAGE_FAILED?>'){
                        Object.keys(data.errors).map(function(key) {
                            console.log()
                            $('#'+key+'_error').show();
                            $('#'+key).addClass('is-invalid');
                            $('#'+key+'_error').text(data.errors[key]);
                            if(key == 'cities_id'){
                                $('#live-search-select').addClass('is-invalid');
                            }
                            if(key == 'categories'){
                                $('#tree-category-select').addClass('is_invalid');
                            }
                        });

                    }
                }

            });
        });

        $('#nav-main-tab').bind('click', function(){
            $('#tree-container').show();
            $('#sub-title').removeClass('color-disabled');
            $('#tree-category-select').removeClass('color-disabled');
            $('#tree-category-select').prop("disabled", false);
            $('#picked-cats-div').removeClass('color-disabled');
            $('#picked-cats-div').prop("disabled", false);
            $('#category-append').removeClass('color-disabled');
            $('#category-append').prop("disabled", false);
            $('#action_select').removeClass('color-disabled');
            $('#action_select').prop("disabled", false);
            $('#cities_id').removeClass('color-disabled');
            $('#cities_id').prop("disabled", false);
            $('#expiry_date').removeClass('color-disabled');
            $('#expiry_date').prop("disabled", false);
            $('#title').removeClass('color-disabled');
            $('#title').prop("disabled", false);
            $('#text').removeClass('color-disabled');
            $('#text').prop("disabled", false);
            $('#price').removeClass('color-disabled');
            $('#price').prop("disabled", false);
            $('#placement_id').prop("disabled", false);
            $('#live-search-select').prop("disabled", false);
            $('#checkbox-select').removeClass('color-disabled');
            $('#publication-button').removeClass('color-disabled-button');
            $('#help-obiavlenia').removeClass('color-disabled');
            $('#placement_id').removeClass('color-disabled');
            $('#live-search-select').removeClass('color-disabled');
            $('#agreement-label').removeClass('color-disabled');
            $('#publication-button').prop("disabled", false);
            $('#agreement').prop("disabled", false);
            $('#agreement-link').removeClass('color-disabled');
            $('#policy-link').removeClass('color-disabled');
            $('#dropzone-container').show();
            $('#file-uploader').show();
        });
        $('#nav-login-tab').bind('click', function(){
            $('#tree-container').hide();
            $('#sub-title').addClass('color-disabled');
            $('#tree-category-select').addClass('color-disabled');
            $('#tree-category-select').prop("disabled", true);
            $('#picked-cats-div').addClass('color-disabled');
            $('#picked-cats-div').prop("disabled", true);
            $('#category-append').addClass('color-disabled');
            $('#category-append').prop("disabled", true);
            $('#action_select').addClass('color-disabled');
            $('#action_select').prop("disabled", true);
            $('#cities_id').addClass('color-disabled');
            $('#cities_id').prop("disabled", true);
            $('#expiry_date').addClass('color-disabled');
            $('#expiry_date').prop("disabled", true);
            $('#title').addClass('color-disabled');
            $('#title').prop("disabled", true);
            $('#text').addClass('color-disabled');
            $('#text').prop("disabled", true);
            $('#price').addClass('color-disabled');
            $('#price').prop("disabled", true);
            $('#live-search-select').prop("disabled", true);
            $('#placement_id').prop("disabled", true);
            $('#checkbox-select').addClass('color-disabled');
            $('#publication-button').addClass('color-disabled-button');
            $('#help-obiavlenia').addClass('color-disabled');
            $('#placement_id').addClass('color-disabled');
            $('#live-search-select').addClass('color-disabled');
            $('#agreement-label').addClass('color-disabled');
            $('#publication-button').prop("disabled", true);
            $('#agreement').prop("disabled", true);
            $('#agreement-link').addClass('color-disabled');
            $('#policy-link').addClass('color-disabled');
            $('#dropzone-container').hide();
            $('#file-uploader').hide();
        });
    });
</script>
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
                        url: '<?= $url ?>',
                        data: {q: $('input#live-search-select').val()},
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
                    _this.val(ui.item[_data.item_label]);
                    event.preventDefault();
                },

                select: function(event, ui) {
                    _hidden_field.val(ui.item.id);
                    _this.val(ui.item[_data.item_label]);
                    _hidden_field.val(ui.item.id);
                    event.preventDefault();
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

