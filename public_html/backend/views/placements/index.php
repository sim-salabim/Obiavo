<?php
Use yii\helpers\Url;

$this->title = 'Типы объявлений';
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">


    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute('create')?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Добавить
        </button>
    </div>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= $this->title?></h3>

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
            <th>Действие</th>
          </tr>
          </thead>
          <tbody>
            <?php foreach ($placements as $place) : ?>
              <tr>
                <td><?php echo $place->id?>
                </td>
                <td><?php echo $place->_text->name?>
                </td>
                <td>
                    <span data-placement="top" data-toggle="tooltip" title="Редактировать">
                        <button class="btn btn-primary btn-xs loadcontent"
                                data-link="<?= Url::toRoute(['update','id' => $place->id])?>">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                    </span>
                    <span data-placement="top" data-toggle="tooltip" title="Удалить">
                        <button class="btn btn-danger btn-xs senddata"
                                data-link="<?= Url::toRoute(['delete','id' => $place->id])?>">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
    <!-- /.box -->
</div>

</div>