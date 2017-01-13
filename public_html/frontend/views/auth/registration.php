<?php
$this->title = 'Регистрация';
?>
<form class="form-horizontal" id="registr-form">

<!-- Имя-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="first_name">Имя</label>
  <div class="col-md-5">
  <input id="first_name" name="first_name" type="text" placeholder="Имя" class="form-control input-md" required="">

  </div>
</div>

<!-- Фамилия-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="last_name">Фамилия</label>
  <div class="col-md-4">
  <input id="last_name" name="last_name" type="text" placeholder="Фамилия" class="form-control input-md" required="">

  </div>
</div>

<!-- Email-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="email">Email</label>
  <div class="col-md-4">
  <input id="email" name="email" type="email" placeholder="email@mail.com" class="form-control input-md" required="">
  </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button class="btn btn-default senddata" >Send invitation</button>
    </div>
</div>

</form>