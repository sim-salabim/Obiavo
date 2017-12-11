
<div class="row">
    <div class="col">
        <?= __('_Contacts')?>
    </div>
</div>
<hr>
<div class="row">
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
<div class="row padding-bottom-10">
    <div class="col">
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
if(isset($model)){
    $files = $model->files;
    $selected_category_id = ($model->categories_id AND $model->categories_id != 0) ? $model->categories_id : null;
    $selected_category = ($selected_category_id) ? \common\models\Category::findOne(['id' => $selected_category_id]) : null;
    $sub_categories = ($selected_category) ? $selected_category->children : null;
    $selected_sub_category = ($model->subcategory AND $model->subcategory != 0) ? \common\models\Category::findOne(['id' => $model->subcategory]) : null;
    $sub_sub_categories = ($selected_sub_category AND $selected_sub_category->children AND $model->subcategory != 0) ? $selected_sub_category->children : null;
    $selected_sub_sub_category = ($model->subsubcategory AND $model->subsubcategory != 0) ? \common\models\Category::findOne(['id' => $model->subsubcategory]) : null;
    $selected_placement_id = ($model->placement_id AND $model->placement_id != 0) ? $model->placement_id : null;
    if($selected_sub_sub_category AND $selected_sub_sub_category->id != 0){
        $placements = $selected_sub_sub_category->placements;
    }else{
        if($selected_sub_category AND $selected_sub_category->id != 0){
            $placements = $selected_sub_category->placements;
        }else{
            if($selected_category AND $selected_category->id != 0){
                $placements = $selected_category->placements;
            }
        }
    }
}
?>
<form id="new-ad-form" method="post" enctype="multipart/form-data" action="/publish-add/">
    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           value="<?=Yii::$app->request->csrfToken?>"/>
    <div class="row">
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select name="categories_id" id="category-select" class="form-control <?php if(Yii::$app->session->getFlash('categories_id_error')){?> is-invalid<?php }?>">
                <option value="0"><?= __('Category') ?></option>
                <? foreach ($categories as $category){?>
                    <? $has_children = (count($category->children) > 0) ? 1 : 0; ?>
                    <option value="<?= $category->id ?>" <? if((isset($model) AND ($model->categories_id AND $model->categories_id != 0)) AND $model->categories_id == $category->id){?>selected<? }?> has_children="<?= $has_children ?>"><?= $category->_text->name ?></option>
                <? } ?>
            </select>
            <?php if(Yii::$app->session->getFlash('categories_id_error')){?>
                <div class="invalid-feedback">
                    <?= __('Required field')?>
                </div>
            <?php } ?>
        </div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select
                name="subcategory"
                id="subcategory"
                class="form-control <?php if(Yii::$app->session->getFlash('subcategory_error')){?> is-invalid<?php }?>">
                <option value="0"><?= __('Subcategory') ?></option>
                <? if($sub_categories){
                    foreach ($sub_categories as $sc){
                        ?>
                        <option value="<?= $sc->id ?>" <? if($selected_sub_category AND $sc->id == $selected_sub_category->id){?>selected<?}?>    <? if(count($sc->children)){?>has_children="1"<? }else{?> has_children="0"<? } ?>><?= $sc->_text->name ?></option>
                      <? }} ?>
            </select>
            <?php if(Yii::$app->session->getFlash('subcategory_error')){?>
                <div class="invalid-feedback">
                    <?= __('Required field') ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6" id="subsubdiv" <?php if(!$sub_sub_categories){?>style="display: none" <? } ?>>
            <select
                name="subsubcategory"
                id="subsubcategory"
                class="form-control <?php if(Yii::$app->session->getFlash('subsubcategory_error')){?> is-invalid<?php }?>">
                <option value=""><?= __('Subcategory') ?></option>
                <? if($sub_sub_categories){
                    foreach ($sub_sub_categories as $ssc){
                    ?>
                    <option value="<?= $ssc->id ?>" <? if($selected_sub_sub_category AND $ssc->id == $selected_sub_sub_category->id){?>selected<?}?> ><?= $ssc->_text->name ?></option>
                <? }} ?>
            </select>
            <?php if(Yii::$app->session->getFlash('subsubcategory_error')){?>
                <div class="invalid-feedback">
                    <?= __('Required field') ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select
                name="placement_id"
                id="action_select"
                class="form-control <?php if(Yii::$app->session->getFlash('placement_id_error')){?> is-invalid<?php }?>">
                <option value="0"><?= __('Action') ?></option>
                <? if($placements){
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
                name="cities_id"
                class="form-control <?php if(Yii::$app->session->getFlash('cities_id_error')){?> is-invalid<?php }?>"
                disabled>
                <option value="0"><?= __('City') ?></option>
                <? foreach($cities as $city){ ?>
                    <option value="<?= $city->id ?>" <? if(\Yii::$app->user->identity->cities_id == $city->id) {?> selected="true" <? } ?>><?= $city->_text->name ?></option>
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
                name="expiry_date"
                class="form-control <?php if(Yii::$app->session->getFlash('expiry_date_error')){?> is-invalid<?php }?>">
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
                class="form-control <?php if(Yii::$app->session->getFlash('title_error')){?> is-invalid<?php }?>"
                type="text"
                name="title"
                <? if(isset($model) AND $model->title){?>
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
                class="form-control <?php if(Yii::$app->session->getFlash('text_error')){?> is-invalid<?php }?>"
                rows="10"
                name="text"><? if(isset($model) AND $model->text){?><?= $model->text ?><? }?></textarea>
            <?php if(Yii::$app->session->getFlash('text_error')){?>
                <div class="invalid-feedback">
                    <?= Yii::$app->session->getFlash('text_error') ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <input
                class="form-control <?php if(Yii::$app->session->getFlash('price_error')){?> is-invalid<?php }?>"
                type="text"
                name="price"
                <? if(isset($model) AND $model->price){?>
                value="<?= $model->price ?>"
                <? }?>
                placeholder="<?= __('Price')?>">
            <?php if(Yii::$app->session->getFlash('price_error')){?>
                <div class="invalid-feedback">
                    <?= Yii::$app->session->getFlash('price_error') ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12" id="file-uploader">
            <?=  $this->render('/partials/_file_uploader.php', ['container_id' => 'file-uploader', 'files' => $files]) ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <button class="btn btn-success senddata col-lg-2 col-md-6 col-sm-12" data-input="#new-ad-form">
                <?= __('Publish') ?>
            </button>
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){

        $('#category-select').on('change', function(){
            $('#subsubdiv').hide();
            cleanSubSelect();
            var category_id = $('#category-select :selected').val();
            if(category_id != 0) {
                getSubCategories(category_id, '#subcategory');
                selectCategoryAction(category_id)
            }
        });
        $('#subcategory').on('change', function(){
            var sub_category_id = $('#subcategory :selected').val();
            var has_children = $('#subcategory :selected').attr('has_children');
            cleanSubSubSelect()
            if(sub_category_id != 0 && has_children == 1){
                $('#subsubdiv').show();
                getSubCategories(sub_category_id, '#subsubcategory');
            }else{
                $('#subsubdiv').hide();
                if($('#category-select :selected').val() != 0){
                    selectCategoryAction($('#category-select :selected').val())
                }
            }

            selectCategoryAction(sub_category_id);
        });
        $('#subsubcategory').on('change', function(){
            var sub_sub_category_id = $('#subsubcategory :selected').val();
            if(sub_sub_category_id != 0){
                selectCategoryAction(sub_sub_category_id)
            }else{
                cleanActionSelect()
                if($('#subcategory :selected').val() != 0){
                    selectCategoryAction($('#subcategory :selected').val())
                }else{
                    if($('#category-select :selected').val() != 0){
                        selectCategoryAction($('#category-select :selected').val())
                    }
                }
            }
        });

        function cleanActionSelect(){
            var action = '<?= __('Action'); ?>';
            $('#action_select').html("<option value='0'>"+action+"</option>");
            $('#subsubcategory option:first').attr('value', '');
        }
        function cleanSubSelect(){
            var action = '<?= __('Subcategory'); ?>';
            $('#s0ubcategory').html("<option value='0'>"+action+"</option>");
            $('#subsubcategory option:first').attr('value', '');
            cleanSubSubSelect();
        }
        function cleanSubSubSelect(){
            var action = '<?= __('Subcategory'); ?>';
            $('#subsubcategory').html("<option value='0'>"+action+"</option>");
            $('#subsubcategory option:first').attr('value', '');
            cleanActionSelect();
        }

        function selectCategoryAction(categoryId) {
            $('#action_select').prop("disabled", true);
            cleanActionSelect();
            if(categoryId != 0) {
                $.ajax({
                    url: '/get-category-placement/',
                    data: {category_id: categoryId},
                    method: 'POST',
                    success: function (data) {
                        actions = JSON.parse(data);
                        var stringToAppend = '<option value="0"><?= __('Action') ?></option>'
                        actions.forEach(function (item) {
                            stringToAppend += '<option value="' + item.id + '">' + item.name + '</option>';
                            $('#action_select option').remove();
                            $('#action_select').html(stringToAppend);
                        });
                        $('#action_select').prop("disabled", false);
                    }
                });
            }
        }

        function getSubCategories(category_id, select_id){
            $('#action_select').prop("disabled", true);
            $.ajax({
                url: '/get-sub-categories/',
                data: {category_id: category_id},
                method: 'POST',
                success: function (data) {
                    var subcats = JSON.parse(data);
                    var stringToAppend = '<option value="0"><?= __('Subcategory') ?></option>'
                    subcats.forEach(function (item, i) {
                        stringToAppend += '<option value="' + item.id + '" has_children="' + item.has_children + '">' + item.name + '</option>';
                    });
                    $(select_id+' option').remove();
                    $(select_id).html(stringToAppend);
                }
            });
        }
    })
</script>
