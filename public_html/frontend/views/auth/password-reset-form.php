<?php 
$this->title = 'Восстановление пароля';
?>
<form class="form-horizontal" method="post" action="<?php  echo Yii::$app->urlManager->createAbsoluteUrl(['auth/reset']); ?>" id="password-recovery-form">

    <!-- Email-->
    <?php if(Yii::$app->session->getFlash('message')){ ?>
        <div class="alert alert-success" role="alert">
            <?= Yii::$app->session->getFlash('message'); ?>
        </div>
    <?php } ?>
    <?php if(Yii::$app->session->getFlash('error') && !Yii::$app->session->getFlash('message')){ ?>
        <div class="alert alert-danger" role="alert">
            <?= Yii::$app->session->getFlash('error'); ?>
        </div>
    <?php } ?>
    <?php if($key_valid){?>
        <div class="form-group validation-errors <?php if(Yii::$app->session->getFlash('pass_error')){?> has-error<?php }?>">
            <label class="col-sm-2 control-label" for="pass">Пароль</label>
            <div class="col-md-4">
                <input id="pass" name="pass"  type="password" class="form-control input-md" >
                <?php if(Yii::$app->session->getFlash('pass_error')){?>
                    <?php foreach(Yii::$app->session->getFlash('pass_error') as $er){?>
                    <span class="help-block"><?= $er ?></span>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="form-group validation-errors <?php if(Yii::$app->session->getFlash('pass_confirm_error')){?> has-error<?php }?>">
            <label class="col-sm-2 control-label" for="pass">Подтвердить пароль</label>
            <div class="col-md-4">
                <input id="pass_confirm" name="pass_confirm" type="password" class="form-control input-md" >
                <?php if(Yii::$app->session->getFlash('pass_confirm_error')){?>
                    <?php foreach(Yii::$app->session->getFlash('pass_confirm_error') as $er){?>
                        <span class="help-block"><?= $er ?></span>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <input type="hidden" name="key" value="<?= $key ?>">
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button class="btn btn-default " data-input="#password-reset-form">Сохранить</button>
            </div>
        </div>
    <?php }else if(!$key_valid && !Yii::$app->session->getFlash('message')){ ?>
        <div class="alert alert-danger" role="alert">
            <?= 'Пожалуйста запросите восстановление пароля еще раз или обратитесь к администраторам.' ?>
        </div>
    <?php } ?>
</form>

