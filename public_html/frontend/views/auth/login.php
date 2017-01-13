<?php
$this->title = 'Авторизация';
?>
<form class="form-horizontal" id="login-form">

<!-- Email-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="email">Email</label>
  <div class="col-md-4">
  <input id="email" name="email" type="email" placeholder="email@mail.com" class="form-control input-md" required="">
  </div>
</div>

<!-- Password-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="password">Пароль</label>
  <div class="col-md-4">
  <input id="password" name="password" type="text" placeholder="Пароль" class="form-control input-md" required="">

  </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button class="btn btn-default senddata" data-input="#login-form">Войти</button>
    </div>
</div>

</form>