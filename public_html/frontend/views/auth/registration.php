<?php
$this->title = 'Регистрация';
?>
<form class="form-horizontal" id="registr-form">

<!-- Email-->
<div class="form-group validation-errors">
  <label class="col-sm-2 control-label" for="email">Email</label>
  <div class="col-md-5">
    <input id="email"
           name="email"
           type="email"
           placeholder="email@mail.com"
           class="form-control input-md"
           required="">
  </div>
</div>

<!-- Password-->
<div class="form-group validation-errors">
  <label class="col-sm-2 control-label" for="password">Пароль</label>
  <div class="col-md-5">
    <input id="password" name="password" type="password" placeholder="Пароль" class="form-control input-md" required="">
  </div>
</div>

<!-- Имя-->
<div class="form-group validation-errors">
  <label class="col-sm-2 control-label" for="first_name">Имя</label>
  <div class="col-md-5">
  <input id="first_name" name="first_name" type="text" placeholder="Имя" class="form-control input-md">

  </div>
</div>

<!-- Фамилия-->
<div class="form-group validation-errors">
  <label class="col-sm-2 control-label" for="last_name">Фамилия</label>
  <div class="col-md-5">
  <input id="last_name" name="last_name" type="text" placeholder="Фамилия" class="form-control input-md">

  </div>
</div>


<div id="x"></div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button class="btn btn-default senddata" data-input="#registr-form">Зарегистрироваться</button>
    </div>
</div>

</form>

<script type="text/javascript">

Core.onFullLoad(function(){

    rct.mount('search-selectpicker',$('#x')[0],{
        options: ['one','two','three'],
        attributes: {
            className: 'yes',
            'data-live-search': true
        }
    });
});
</script>