<h3><?php
    $this->title = __('Add ad');
    $model = Yii::$app->session->getFlash('model');
    ?>
</h3>
<hr>
<div class="col-md-12 alert alert-light" role="alert"><?= __('Please sign in first') ?></div>
<form class="form-horizontal" method="post" id="login-form">
    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           value="<?=Yii::$app->request->csrfToken?>"/>
    <!-- Email-->
    <div class="form-group validation-errors ">
        <div class="form-group">
            <input
                id="email"
                name="email"
                type="email"
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
<form class="disabled-form" id="new-ad-form">

    <div class="row">
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select disabled class="form-control ">
                <option value="0"><?= __('Category') ?></option>
            </select>
        </div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select
                disabled
                class="form-control">
                <option value="0"><?= __('Subcategory') ?></option>
            </select>
        </div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select
                disabled
                class="form-control">
                <option value="0"><?= __('Action') ?></option>

            </select>

        </div>
        <div class="w-100"></div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select
                disabled
                class="form-control"
                disabled>
                <option value="0"><?= __('City') ?></option>
            </select>
        </div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select
                disabled
                class="form-control">
                <option value="0" selected><?= __('Time') ?></option>
            </select>
        </div>
        <div class="w-100"></div>
    </div>
    <hr>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <input
                disabled
                class="form-control"
                type="text"
                placeholder="<?= __('Title')?>">
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <textarea
                disabled
                class="form-control"
                rows="10"
                ></textarea>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <input
                class="form-control"
                disabled
                type="text"
                placeholder="<?= __('Price')?>">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <button disabled class="btn btn-success senddata col-lg-2 col-md-6 col-sm-12">
                <?= __('Publish') ?>
            </button>
        </div>
    </div>
</form>
