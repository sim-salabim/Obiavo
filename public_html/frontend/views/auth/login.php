<?php
$this->title = __('Authorization');
?>
<form class="form-horizontal" method="post" id="login-form">
    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           value="<?=Yii::$app->request->csrfToken?>"/>
<!-- Email-->
<div class="form-group validation-errors ">
  <label class="col-sm-2 control-label" for="email">Email</label>
  <div class="col-md-4">
    <input id="email" name="email" type="email" placeholder="email@mail.com" class="form-control input-md <?php if(Yii::$app->session->getFlash('email_error')){?> is-invalid<?php }?>" required="">
      <?php if(Yii::$app->session->getFlash('email_error')){?>
          <div class="invalid-feedback">
              <?= Yii::$app->session->getFlash('email_error') ?>
          </div>
      <?php } ?>
  </div>
</div>

<!-- Password-->
<div class="form-group validation-errors ">
  <label class="col-sm-2 control-label" for="password"><?= __('Password') ?></label>
  <div class="col-md-4">
    <input id="password" name="password" type="password" placeholder="<?= __('Password') ?>" class="form-control input-md <?php if(Yii::$app->session->getFlash('password_error')){?> is-invalid<?php }?>" required="">
      <?php if(Yii::$app->session->getFlash('password_error')){?>
          <div class="invalid-feedback">
              <?= Yii::$app->session->getFlash('password_error') ?>
          </div>
      <?php } ?>
  </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <a href="<?= yii\helpers\Url::toRoute('/recovery') ?>"><?= __('Forgot your password?') ?></a>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button class="btn btn-default senddata" data-input="#login-form"><?= __('Sign in') ?></button>
    </div>
</div>

</form>

