<h3><?php
    $this->title = __('Authorization');
    ?>
</h3>
<form class="form-horizontal" method="post" id="login-form">
    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           value="<?=Yii::$app->request->csrfToken?>"/>
    <!-- Email-->
    <div class="form-group validation-errors ">
        <div class="form-group">
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
        <div class="form-group">
            <input id="password" name="password" type="password" placeholder="<?= __('Password') ?>" class="form-control input-md <?php if(Yii::$app->session->getFlash('password_error')){?> is-invalid<?php }?>" required="">
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

    <div class="form-group">
        <button class="btn btn-success" data-input="#login-form"><?= __('Sign in') ?></button>

    </div>

</form>
