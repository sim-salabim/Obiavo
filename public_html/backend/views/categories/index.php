<?php
Use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
$parentID = $parentCategoryId;
/**
 * Наработки хлебных крошек.
 * Надо переделать в виджет
 */
$categoryBreadcrumbsArray = [];

while(!is_null($parentID)){
    $categoryBreadcrumbs = common\models\Category::find()
                                ->where(['id' => $parentID])
                                ->one();

    $parentID = $categoryBreadcrumbs->parent_id;

    array_unshift($categoryBreadcrumbsArray, $categoryBreadcrumbs);
}

if (!empty($categoryBreadcrumbsArray)){

    $currentCategoryBreadcrumb = array_pop($categoryBreadcrumbsArray);

    foreach ($categoryBreadcrumbsArray as $categoryBreadcrumb){
        $this->params['breadcrumbs'][] = [
            'label' => $categoryBreadcrumb->techname,
            'url' => Url::toRoute(['index','id' => $categoryBreadcrumb->id])
        ];
    }

    $this->params['breadcrumbs'][] = $currentCategoryBreadcrumb->techname;
    $homeLink = ['label' => 'Категории', 'url' => '/categories'];
} else {
    $homeLink = 'Категории';
}
//.......
// Выводим цепочку навигации
echo Breadcrumbs::widget([
        'homeLink' => $homeLink,
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
    ]);
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">

    <button type="button" class="btn btn-primary btn-lg loadcontent" data-link="<?= Url::toRoute(['append-category','id' => $parentCategoryId])?>">Добавить категорию</button>

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
                <td><a href="<?php echo Url::toRoute(['index','id' => $category->id])?>"><?php echo $category->techname?></td>
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

</div>