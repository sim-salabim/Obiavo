<?php
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\models\City;

$url = \yii\helpers\Url::toRoute('cities/search-cities-for-select');
$selectCity = __('Select a city');
$this->title = __('Registration');
?>
<form class="form-horizontal" method="post" id="registr-form">
    <?php  if(Yii::$app->session->getFlash('message')){ ?>
        <div class="alert alert-success" role="alert">
            <?= Yii::$app->session->getFlash('message'); ?>
        </div>
    <?php  } ?>
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
      <?php if(Yii::$app->session->getFlash('first_name_error')){?>
          <div class="invalid-feedback">
              <?= Yii::$app->session->getFlash('first_name_error') ?>
          </div>
      <?php } ?>
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
      <?php if(Yii::$app->session->getFlash('last_name_error')){?>
          <div class="invalid-feedback">
              <?= Yii::$app->session->getFlash('last_name_error') ?>
          </div>
      <?php } ?>
  </div>
</div>

<!-- Город-->
<div class="form-group validation-errors">
    <label class="col-sm-2 control-label" for="city"><?= __('City') ?></label>
    <div class="col-md-5">
        <input
            class="form-control bs-autocomplete <?php if(Yii::$app->session->getFlash('city_error')){?> is-invalid<?php }?>"
            id="live-search-select"
            value=""
            placeholder="<?= $selectCity ?>"
            type="text"
            data-hidden_field_id="hidden-city"
            data-item_id="live-search-select"
            data-item_label="text"
            autocomplete="off">
        <?php if(Yii::$app->session->getFlash('city_error')){?>
            <div class="invalid-feedback">
                <?= Yii::$app->session->getFlash('city_error') ?>
            </div>
        <?php } ?>
        <input type="hidden" id="hidden-city" name="cities_id" value="">
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
      <?php if(Yii::$app->session->getFlash('email_error')){?>
          <div class="invalid-feedback">
              <?= Yii::$app->session->getFlash('email_error') ?>
          </div>
      <?php } ?>
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
      <?php if(Yii::$app->session->getFlash('password_error')){?>
          <div class="invalid-feedback">
              <?= Yii::$app->session->getFlash('password_error') ?>
          </div>
      <?php } ?>
  </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button class="btn btn-default senddata" data-input="#registr-form"><?= __('Sign up') ?></button>
    </div>
</div>

</form>

<script type="text/javascript">
$.widget("ui.autocomplete", $.ui.autocomplete, {

    _renderMenu: function(ul, items) {
        var that = this;
        ul.attr("class", "nav nav-pills nav-stacked  bs-autocomplete-menu list-group");
        $.each(items, function(index, item) {
            that._renderItemData(ul, item);
        });
    },

    _resizeMenu: function() {
        var ul = this.menu.element;
        ul.outerWidth(Math.min(
            ul.width("").outerWidth() + 1,
            this.element.outerWidth()
        ));
    }

});

(function() {
    $('.bs-autocomplete').each(function() {
        var _this = $(this),
            _data = _this.data(),
            _search_data = [],
            _visible_field = $('#' + _data.item_id),
            _hidden_field = $('#' + _data.hidden_field_id);


        _this.after('<div class="bs-autocomplete-feedback form-control-feedback"><div class="loader"><?= __('Search...') ?></div></div>')
            .parent('.form-group').addClass('has-feedback');

        var feedback_icon = _this.next('.bs-autocomplete-feedback');
        feedback_icon.hide();

        _this.autocomplete({
            minLength: 3,
            autoFocus: true,

            source: function(request, response) {
                _hidden_field.val('');
                $.ajax({
                    dataType: "json",
                    type : 'POST',
                    url: '<?= $url ?>',
                    data: {q: $('input#live-search-select').val()},
                    success: function(data) {
                        _search_data = data
                        $('input.suggest-user').removeClass('ui-autocomplete-loading');
                        if(_search_data.length == 0){
                            _hidden_field.val('');
                        }
                        response(data);
                    }
                });
            },

            search: function() {
                feedback_icon.show();
                _hidden_field.val('');
            },

            response: function() {
                feedback_icon.hide();
            },

            focus: function(event, ui) {
                _this.val(ui.item[_data.item_label]);
                event.preventDefault();
            },

            select: function(event, ui) {
                _hidden_field.val(ui.item.id);
                _this.val(ui.item[_data.item_label]);
                _hidden_field.val(ui.item[_data.item_id]);
                event.preventDefault();
            },
            close: function( event, ui ) {
                console.log('close');
                if(_search_data.length != 0){
                    console.log('da');
                    _hidden_field.val(_search_data[_visible_field.val()].id);
                }
            }
        })
            .data('ui-autocomplete')._renderItem = function(ul, item) {
            return $('<li class="list-group-item" ></li>')
                .data("item.autocomplete", item)
                .append('<a>' + item[_data.item_label] + '</a>')
                .appendTo(ul);
        };
    });
})();
</script>