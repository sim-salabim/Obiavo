<?php
Use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$country = $region->country;

$this->params['breadcrumbs'][] = [
                                'label' => "Регионы {$country->countryText->name_rp}",
                                'url' => Url::toRoute(['regions/','country_id' => $country->id])
                        ];
$this->params['breadcrumbs'][] = "Города {$region->regionText->name_rp}";
$homeLink = ['label' => 'Страны', 'url' => '/countries'];
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">


    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['append','region_id' => $region->id])?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Создать новый пункт
        </button>
    </div>

    <?php echo Breadcrumbs::widget([
        'homeLink' => $homeLink,
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
    ]);?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">Города</h3>

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
            <th>Домен</th>
            <th>Действие</th>
          </tr>
          </thead>
          <tbody>
            <?php foreach ($cities as $city) : ?>
              <tr>
                <td><?php echo $city->id?></td>
                <td><?php echo $city->cityText->name?>
                    <?= backend\helpers\ActiveLabel::status($city->active, [
                        'active' => 'активно',
                        'inactive' => 'не активно'
                    ])?>
                </td>
                <td><?php echo $city->domain?></td>
                <td>
                    <span data-placement="top" data-toggle="tooltip" title="Редактировать">
                        <button class="btn btn-primary btn-xs loadcontent"
                                data-link="<?= Url::toRoute(['edit','id' => $city->id])?>">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                    </span>
                    <span data-placement="top" data-toggle="tooltip" title="Удалить">
                        <button class="btn btn-danger btn-xs senddata"
                                data-link="<?= Url::toRoute(['delete','id' => $city->id])?>">
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