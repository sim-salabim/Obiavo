<?php 
$this->title = 'Восстановление пароля';
?>
<form class="form-horizontal" method="post" id="password-recovery-form">
    <?php  if(Yii::$app->session->getFlash('message')){ ?>
        <div class="alert alert-success" role="alert">
            <?= Yii::$app->session->getFlash('message'); ?>
        </div>
    <?php  } ?>
    <div class="form-group validation-errors ">
        <label class="col-sm-2 control-label" for="email">Email</label>
        <div class="col-md-4">
            <input id="email" name="email" type="email" placeholder="" class="form-control input-md <?php if(Yii::$app->session->getFlash('recovery_error')){?> is-invalid<?php }?>" >
            <?php if(Yii::$app->session->getFlash('recovery_error')){?>
                <?php foreach(Yii::$app->session->getFlash('recovery_error') as $er){?>
                    <div class="invalid-feedback">
                        <?= $er ?>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button class="btn btn-default senddata" data-input="#password-recovery-form">Подтвердить</button>
        </div>
    </div>

</form>

