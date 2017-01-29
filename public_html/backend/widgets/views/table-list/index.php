<?php
Use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
?>

<div id="loadcontent-container" style="display: none"></div>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= $title?></h3>

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
            <?php foreach($items['label'] as $label) { ?>
              <th><?= $label?></th>
            <?php }?>
          </tr>
          </thead>
          <tbody>
            <?php foreach ($items['content'] as $cells) : ?>
              <tr>
                <?php foreach ($cells as $content) { ?>
                    <td><?php echo $content;?></td>
                <?php }?>

                <?php /**
                <td><a href="<?php echo Url::toRoute(['cities/','region_id' => $region->id])?>">
                        <?php echo $region->regionText->name?>
                    </a>
                    <?= backend\helpers\ActiveLabel::status($region->active, [
                        'active' => 'активно',
                        'inactive' => 'не активно'
                    ])?>
                </td>
                <td><?php echo $region->domain?></td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default" data-toggle="dropdown" aria-expanded="true">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>RU
                        </button>
                        <button type="button" class="btn btn-default" data-toggle="dropdown" aria-expanded="true">
                        <span class="glyphicon glyphicon-minus" style="color:red" aria-hidden="true"></span>EN
                        </button>
                    </div>
                </td>
                <td>
                    <span data-placement="top" data-toggle="tooltip" title="Редактировать">
                        <button class="btn btn-primary btn-xs loadcontent"
                                data-link="<?= Url::toRoute(['edit','id' => $region->id])?>">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                    </span>
                    <span data-placement="top" data-toggle="tooltip" title="Удалить">
                        <button class="btn btn-danger btn-xs senddata"
                                data-link="<?= Url::toRoute(['delete','id' => $region->id])?>">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </span>
                </td>
                 *
                 */?>
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