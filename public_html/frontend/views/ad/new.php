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
            <button id="tree-category-select" class="form-control text-align-left cursor-pointer <? if(!$user){?> color-disabled<? } ?>  <?php if(Yii::$app->session->getFlash('categories_error')){?> is-invalid <? } ?>" <? if(!$user){?> disabled<? } ?>>
                <?= __('Category tree selection') ?>
            </button>
            <?php if(Yii::$app->session->getFlash('categories_error')){?>
                <div class="invalid-feedback">
                    <?= __('Pick at least one category') ?>
                </div>
            <?php } ?>
            <div class="form-control" id="tree-container" style="display:none">
            </div>
        </div>
        </div>
        <hr class="width-100">
        <div class="col-12 sub-title padding-left0  <? if(!$user){?> color-disabled<? } ?>" ><?= __('Picked categories') ?></div>
        <div id="category-append" class="<? if(!$user){?> color-disabled<? } ?>" >
            <? if($model and $model->categories){
                foreach($model->categories as $cat_id){
                    $selected_cat = \common\models\Category::find()->where(['id'=>$cat_id])->one();
            ?>
                    <span id="checked-<?= $selected_cat->id ?>" class="js_tree_el"><input type="hidden" name="categories[]" value="<?= $selected_cat->id ?>" class="js_tree_el"><?= $selected_cat->_text->name ?> <i style="cursor: pointer" class="fa fa-times js_tree_el" aria-hidden="true" id="checked-close-<?= $selected_cat->id ?>" onclick="closeCheckedAndTree(<?= $selected_cat->id ?>)"></i></span><br class="js_tree_el">
            <? }}else{
                $class = '';
                if(!$user){ $class = "color-disabled";}
                echo __('Pick a category. The category firstly picked wil be the main one for the ad.')." ".__("You can pick free only")." ".countString(\common\models\Settings::find()->one()->categories_limit, [__("pick_one_category"), __("pick_two_category"),__("pick_more_category")]).". <a href='/help-obiavlenya/' class='{$class}'>".__("Get details about posting ads?")."</a>";
            } ?>
        </div>
        <hr class="width-100">
        <?= $this->render('/scripts/tree-select', ['categories' => $categories, 'categories_limit' => $categories_limit, "if_user_logged" => $if_user_logged,
         "selected_categories" => $selected_categories]); ?>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <select
                <? if(!$user){?> disabled<? } ?>
                name="placement_id"
                id="action_select"
                class="form-control <?php if(Yii::$app->session->getFlash('placement_id_error')){?> is-invalid<?php }?> <? if(!$user){?>color-disabled<? } ?>">
                <option value="0"><?= __('Action') ?></option>
                <? if($placements and $user){
                    foreach ($placements as $pl){
                        ?>
                        <option value="<?= $pl->id ?>" <? if($model AND $pl->id == $model->placement_id){?>selected<?}?> ><?= $pl->_text->name ?></option>
                    <? }} ?>
            </select>
            <?php if(Yii::$app->session->getFlash('placement_id_error')){?>
                <div class="invalid-feedback">
                    <?= __('Required field') ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
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
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <select
                disabled
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
                <option value="2419200" <? if((isset($model) AND $model->expiry_date) AND $model->expiry_date == 2419200){?>selected<? }?>selected><?= __('One month') ?></option>
                <? } ?>
            </select>
            <?php if(Yii::$app->session->getFlash('expiry_date_error')){?>
                <div class="invalid-feedback">
                    <?= __('Required field') ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <hr class="margin-top-0">
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
                placeholder="<?= __('Write your ad\'s text') ?>"
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
        <div class="form-group col-lg-12 col-sm-12 col-md-12 "  >
            <div class="dropzone" id="file-uploader"></div>
        </div>
            <?=  $this->render('/partials/_file_uploader.php', ['container_id' => 'file-uploader', 'files' => $files]) ?>
        <? }?>
    </div>
    <hr>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <button <? if(!$user){?> disabled<? } ?> class="btn btn-success senddata col-lg-2 col-md-6 col-sm-12 <? if(!$user){?>color-disabled-button<? } ?> publish-button" data-input="#new-ad-form">
                <?= __('Publish') ?>
            </button>
        </div>
    </div>
</form>
</div>

