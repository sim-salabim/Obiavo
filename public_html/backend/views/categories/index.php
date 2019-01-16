<?php
Use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use backend\widgets\TableList;
use yii\helpers\Html;

$this->title = 'Категории';
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">


    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['create','parent_id' => $categoryParent->id])?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Создать новый пункт
        </button>
        <button class="btn btn-primary" id="deactivate-cats">Деактивировать категориии</button>
        <button class="btn btn-primary" id="activate-cats">Aктивировать категориии</button>
    </div>

    <?= $this->render('breadcrumbs', ['category' => $categoryParent]);?>

<?= TableList::widget([
    'title' => 'Категории',
    'data'  => $categories,
    'columns' => [
        [
            'attribute'    => 'id',
            'label'        => 'ID',
        ],
        [
            'label'        => 'Название',
            'content'      => function($model){
                $html = Html::a($model->techname, Url::toRoute(['/categories','id' => $model->id]));
                $html .= backend\helpers\ActiveLabel::status($model->active, [
                        'active' => 'активно',
                        'inactive' => 'не активно'
                        ]);

                return $html;
            },
        ],
        [
            'label'        => 'Осцновная соц. группа',
            'content'      => function($model){
                              return ($model->socialNetworkGroupsMain) ? $model->socialNetworkGroupsMain->name : '<span class="badge badge-warning">Не выбранa</span>';
            },
        ],
        [
            'label'        => 'Тексты',
            'format'       => TableList::TYPE_MULTI_BUTTON,
        ],
        [
            'label'        => 'SEO типов',
            'format'       => TableList::TYPE_OPT_BUTTON,
            'buttons'      => [
                'update-seo-attached',
            ]
        ],
        [
            'label'        => 'Управление',
            'format'       => TableList::TYPE_OPT_BUTTON,
            'buttons'      => [
                'update',
                'delete',
            ]
        ],
    ]
]);?>

</div>
<?
$parent_category_id = $categoryParent->id ?: null;
?>
<script>
    $(document).ready(function(){
        $("#deactivate-cats").on("click", function(){
            alert("Вы действительно хотите деактивировать все категории на данной странице рекурсивно?");
            $("#deactivate-cats").text("Ожидание...");
            deactivate(0);
        });

        $("#activate-cats").on("click", function(){
            alert("Вы действительно хотите активировать все категории на данной странице рекурсивно?");
            $("#activate-cats").text("Ожидание...");
            activate(0);
        });
    });
    function deactivate(offset){
        var rootCategory = '<?= $parent_category_id ?>';
        $.ajax({
            dataType: "json",
            type : 'POST',
            url: '<?= \yii\helpers\Url::toRoute('categories/inactive-child-categories') ?>',
            data: {offset: offset, root_category: rootCategory},
            success: function(data) {
                console.log(data.message);
                if(data.message === 'error'){
                    alert("Возникла ошибка");
                    location.reload();
                }
                if(data.message === 'finish'){
                    $("#deactivate-cats").text("Деактивировать категории");
                    alert('завершено');
                    location.reload();
                }
                deactivate(data.message);
            }
        });
    }

    function activate(offset){
        var rootCategory = '<?= $parent_category_id ?>';
        $.ajax({
            dataType: "json",
            type : 'POST',
            url: '<?= \yii\helpers\Url::toRoute('categories/active-child-categories') ?>',
            data: {offset: offset, root_category: rootCategory},
            success: function(data) {
                console.log(data.message);
                if(data.message === 'error'){
                    alert("Возникла ошибка");
                    location.reload();
                }
                if(data.message === 'finish'){
                    $("#activate-cats").text("Активировать категории");
                    alert('завершено');
                    location.reload();
                }
                activate(data.message);
            }
        });
    }
</script>