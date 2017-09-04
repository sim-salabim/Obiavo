<?php
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\models\City;

$url = \yii\helpers\Url::toRoute('cities/search-cities-for-select');
$this->title = __('Registration');
?>
<form class="form-horizontal" method="post" id="registr-form">

<!-- Имя-->
<div class="form-group validation-errors">
  <label class="col-sm-2 control-label" for="first_name"><?= __('Name') ?></label>
  <div class="col-md-5">
  <input
      id="first_name"
      name="first_name"
      type="text"
      placeholder="<?= __('Name') ?>"
      class="form-control input-md <?php if(Yii::$app->session->getFlash('first_name_error')){?> is-invalid<?php }?>">

  </div>
</div>

<!-- Фамилия-->
<div class="form-group validation-errors">
  <label class="col-sm-2 control-label" for="last_name"><?= __('Surname') ?></label>
  <div class="col-md-5">
  <input
      id="last_name"
      name="last_name"
      type="text"
      placeholder="<?= __('Surname') ?>"
      class="form-control input-md <?php if(Yii::$app->session->getFlash('last_name_error')){?> is-invalid<?php }?>">

  </div>
</div>

<!-- Город-->
<div class="form-group validation-errors">
    <label class="col-sm-2 control-label" for="city"><?= __('City') ?></label>
    <div class="col-md-5">
      <?=
         Select2::widget([
             'name' => __('City'),
             'options' => ['placeholder' => __('Select a city')],
             'theme' => Select2::THEME_CLASSIC,
             'pluginOptions' => [
                 'allowClear' => true,
                 'minimumInputLength' => 1,
                 'language' => [
                     'errorLoading' => new JsExpression("function () { return ".__('Nothing found')."; }"),
                 ],
                 'ajax' => [
                     'url' => $url,
                     'method' => 'post',
                     'dataType' => 'json',
                     'data' => new JsExpression('function(params) { return {q:params.term}; }')
                 ],
                 'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                 'templateResult' => new JsExpression('function(city) { return city.text; }'),
                 'templateSelection' => new JsExpression('function (city) { console.log(city); }'),
             ],
         ]);?>
  </div>
</div>

<!-- Email-->
<div class="form-group validation-errors">
  <label class="col-sm-2 control-label" for="email">Email</label>
  <div class="col-md-5">
    <input id="email"
           name="email"
           type="email"
           placeholder="email@mail.com"
           class="form-control input-md <?php if(Yii::$app->session->getFlash('email_error')){?> is-invalid<?php }?>"
           required="">
  </div>
</div>

<!-- Password-->
<div class="form-group validation-errors">
  <label class="col-sm-2 control-label" for="password"><?= __('Password') ?></label>
  <div class="col-md-5">
    <input
        id="password"
        name="password"
        type="password"
        placeholder="<?= __('Password') ?>"
        class="form-control input-md <?php if(Yii::$app->session->getFlash('password_error')){?> is-invalid<?php }?>"
        required="">
  </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button class="btn btn-default senddata" data-input="#registr-form"><?= __('Sign up') ?></button>
    </div>
</div>

</form>

<script type="text/javascript">
$(document).ready(function(){
    $("#live-search-select").select2({
        ajax: {
            url: "<?= \yii\helpers\Url::toRoute('cities/search-cities');?>",
            cache: true
        }
    });
})
</script>