<?php
Use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use backend\widgets\TableList;
use yii\helpers\Html;

$breadcrumbs = '';

if ($region){
$country = $region->country;

$this->params['breadcrumbs'][] = [
                                'label' => "Регионы {$country->_text->name_rp}",
                                'url' => Url::toRoute(['regions/','country_id' => $country->id])
                        ];
$this->params['breadcrumbs'][] = "Города {$region->_text->name_rp}";
$homeLink = ['label' => 'Страны', 'url' => '/countries'];

$breadcrumbs = Breadcrumbs::widget([
                'homeLink' => $homeLink,
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
            ]);

}
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">


    <div class="well">
        <button class="btn btn-primary loadcontent"
                data-link="<?= Url::toRoute(['create','id' => $region->id])?>">
            <i class="fa fa-fw -square -circle fa-plus-square"></i>
            Создать новый пункт
        </button>
    </div>

    <?= $breadcrumbs;?>

<?= TableList::widget([
    'title' => 'Города',
    'data'  => $cities,
    'columns' => [
        [
            'attribute'    => 'id',
            'label'        => 'ID',
        ],
        [
            'label'        => 'Название',
            'content'      => function($model){
                $html = $model->_text->name;
                $html .= backend\helpers\ActiveLabel::status($model->active, [
                        'active' => 'активно',
                        'inactive' => 'не активно'
                        ]);

                return $html;
            },
        ],
        [
            'label'        => 'Тексты',
            'format'       => TableList::TYPE_MULTI_BUTTON,
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