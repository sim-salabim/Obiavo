<?php
Use yii\helpers\Url;
?>

<button type="button" class="btn btn-primary btn-lg">Добавить категорию</button>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">Категории</h3>

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
            <th>Название</th>
            <th>Status</th>
          </tr>
          </thead>
          <tbody>
            <?php foreach ($categories as $category) : ?>
              <tr>
                <td><?php echo $category->id?></td>
                <td><a href="<?php echo Url::toRoute(['get-children-categories','id' => $category->id])?>"><?php echo $category->techname?></td>
                <td><?php echo ($category->active) ? 'Активно' : 'Не активно'?></td>
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