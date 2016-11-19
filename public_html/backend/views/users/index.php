<?php
Use yii\helpers\Url;
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">


    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['create'])?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Добавить пользователя
        </button>
    </div>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">Страны</h3>

        <div class="box-tools">
          <div class="input-group">
              <input name="table_search" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search" type="text">
              <div class="input-group-btn">
                  <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
              </div>
          </div>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
          <thead>
          <tr>
            <th>ID</th>
            <th>email</th>
            <th>ФИО</th>
            <th>Город</th>
<!--            <th>Домен</th>
            <th>Действие</th>-->
          </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user) :?>
              <tr>
                <td><?php echo $user->id?></td>
                <td><?= $user->email?></td>
                <td><?= $user->fullName?></td>
                <td><?= $user->cities->cityText->name?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
        <div class="box-footer clearfix">
            <ul class="pagination pagination-sm no-margin pull-right">
                <li><a href="#">&laquo;</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">&raquo;</a></li>
            </ul>
        </div>
        <!-- /.box-footer -->
    </div>
    <!-- /.box -->
</div>

</div>