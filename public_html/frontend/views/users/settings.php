<?
$this->title = __('My settings');
?>
<div class="row">
    <div class="col">
        <?= __('Personal data')?>
    </div>
</div>
<hr>
<form id="my-settings" method="POST" action="/nastroiki">
    <div class="row">
        <?php  if(Yii::$app->session->getFlash('message')){ ?>
            <div class="alert alert-success col-12" role="alert">
                <?= Yii::$app->session->getFlash('message'); ?>
            </div>
        <?php  } ?>
        <div class="form-group col-lg-4 col-sm-12 col-md-6"><?= __('_Email'); ?></div>
        <div class="form-group col-lg-8 col-sm-12 col-md-6">
            <?= Yii::$app->user->getIdentity()->email; ?>
        </div>
        <div class="form-group col-lg-4 col-sm-12 col-md-6"><?= __('Your name'); ?></div>
        <div class="form-group col-lg-8 col-sm-12 col-md-6">
            <?= Yii::$app->user->getIdentity()->getFullName() ?>
        </div>
        <div class="form-group col-lg-4 col-sm-12 col-md-6"><?= __('Phone Number'); ?></div>
        <div class="form-group col-lg-6 col-sm-12 col-md-6">
            <input
                class="form-control <?php if(Yii::$app->session->getFlash('phone_number_error')){?> is-invalid<?php }?>"
                type="text"
                name="phone_number"
                value="<?= Yii::$app->user->getIdentity()->phone_number ?>"
                placeholder="<?= __('Phone Number')?>">
            <?php if(Yii::$app->session->getFlash('phone_number_error')){?>
                <div class="invalid-feedback">
                    <?= Yii::$app->session->getFlash('phone_number_error') ?>
                </div>
            <?php } ?>
        </div>

    </div>
    <hr>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <button class="btn btn-success senddata col-lg-2 col-md-6 col-sm-12" data-input="#my-settings">
                <?= __('Change') ?>
            </button>
        </div>
    </div>
</form>
