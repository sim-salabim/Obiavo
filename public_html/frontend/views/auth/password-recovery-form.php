<h3><?php
    $this->title = __('Password recovery');
    $model = Yii::$app->session->getFlash('model');
    ?>
</h3>
<?
$advertising_code = \common\models\Advertising::getCodeByPlacement(\common\models\Advertising::PLACEMENT_TECHNICAL_PAGES_ABOVE_TEXT);
?>
<? if($advertising_code ){ ?>
    <div class="col-lg-12 padding-left0 padding-bottom-10">
        <?= $advertising_code; ?>
    </div>
<? } ?>
<form class="form-horizontal" method="post" id="password-recovery-form">

    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           value="<?=Yii::$app->request->csrfToken?>"/>
    <?php  if(Yii::$app->session->getFlash('message')){ ?>
        <div class="alert alert-success" role="alert">
            <?= Yii::$app->session->getFlash('message'); ?>
        </div>
    <?php  }?>
    <div class="form-group validation-errors ">

        <div class="form-group">
            <input
                id="email"
                name="email"
                <? if(isset($model) AND $model->email){?>
                    value="<?= $model->email ?>"
                <? }?>
                placeholder=""
                class="form-control input-md <?php if(Yii::$app->session->getFlash('recovery_error')){?> is-invalid<?php }?>" >
            <?php if(Yii::$app->session->getFlash('recovery_error')){?>
                <?php foreach(Yii::$app->session->getFlash('recovery_error') as $er){?>
                    <div class="invalid-feedback">
                        <?= $er ?>
                    </div>
                <?php } ?>
            <?php } ?>
            <div class="grey-text">
                <?= __('Password recovery steps will be sent on your email'); ?>
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group">

        <button class="btn btn-success" data-input="#password-recovery-form"><?= __('Confirm') ?></button>

    </div>

</form>
<?
$advertising_code = \common\models\Advertising::getCodeByPlacement(\common\models\Advertising::PLACEMENT_TECHNICAL_PAGES_BELOW_TEXT);
?>
<? if($advertising_code ){ ?>
    <div class="col-lg-12 padding-left0">
        <?= $advertising_code; ?>
    </div>
<? } ?>