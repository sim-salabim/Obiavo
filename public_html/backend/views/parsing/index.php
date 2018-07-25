<div class="box">
    <div class="box-header">
        <h3 class="box-title">Парсинг</h3>

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
                <th>Парсинг</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><a href="<?= \yii\helpers\Url::toRoute(["parsing/source"])?>">Парсинг исходников 1</a></td>
            </tr>
            <tr>
                <td><a href="<?= \yii\helpers\Url::toRoute(["parsing/categories-live-tables"])?>">Категории 3</a></td>
            </tr>
            <tr>
                <td><a href="<?= \yii\helpers\Url::toRoute(["parsing/fix-placements"])?>">Парсинг исходников placements 2 </a></td>
            </tr>
            <tr>
                <td><a href="<?= \yii\helpers\Url::toRoute(["parsing/placement"])?>">Текст для типов обьявлений 4</a></td>
            </tr>
            <tr>
                <td><a href="<?= \yii\helpers\Url::toRoute(["parsing/categories"])?>">Падежи категорий</a></td>
            </tr>
            <tr>
                <td><a href="<?= \yii\helpers\Url::toRoute(["parsing/categories-text"])?>">Teksty категорий</a></td>
            </tr>
            <tr>
                <td><a href="<?= \yii\helpers\Url::toRoute(["parsing/categories-placement-text"])?>">Teksty плайсментов</a></td>
            </tr>
            <tr>
                <td><a href="<?= \yii\helpers\Url::toRoute(["parsing/h"])?>">H2</a></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>