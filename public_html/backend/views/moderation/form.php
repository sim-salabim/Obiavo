
<?php
/**
 * Форма для добавления и редактирования языков
 * @var object $language - Объект редактируемого языка (пустой объект, если добавляем)
 * @var string toUrl ссылку куда отправлять данные на обработку
 */

use backend\widgets\Form;
use common\models\Category;
use common\models\Language;

$current_city = [];
$current_city['id'] = $ad->city->id;
$current_city['title'] = $ad->city->_text->name;
$expiry_options = [];
$expiry_options[0]['id'] = \common\models\Ads::DATE_RANGE_ONE_MONTH;
$expiry_options[0]['name'] = 'Один месяц';
$expiry_options[1]['id'] = \common\models\Ads::DATE_RANGE_THREE_MONTHS;
$expiry_options[1]['name'] = 'Три месяца';
$expiry_options[2]['id'] = \common\models\Ads::DATE_RANGE_SIX_MONTHS;
$expiry_options[2]['name'] = 'Шесть месяцев';
$expiry_options[3]['id'] = \common\models\Ads::DATE_RANGE_ONE_YEAR;
$expiry_options[3]['name'] = 'Один год';
$expiry_options[4]['id'] = \common\models\Ads::DATE_RANGE_THREE_YEARS;
$expiry_options[4]['name'] = 'Три года';
$expiry_options[5]['id'] = \common\models\Ads::DATE_RANGE_UNLIMITED;
$expiry_options[5]['name'] = 'Неограничено';
$validity = null;
if($ad){
    $created_at_mnth = date('n',$ad->created_at);
    $created_at_yr = date('Y',$ad->created_at);
    $exp_date_mnth = date('n',$ad->expiry_date);
    $exp_date_yr = date('Y',$ad->expiry_date);
    if(//если expiry_date 1 месяц
        ($created_at_yr == $exp_date_yr and $exp_date_mnth - $created_at_mnth == 1) or
        ($created_at_yr < $exp_date_yr and $exp_date_mnth == 12 and $created_at_mnth == 1)
    ){
        $validity = \common\models\Ads::DATE_RANGE_ONE_MONTH;
    }
    if(
        ($created_at_yr == $exp_date_yr and $exp_date_mnth - $created_at_mnth == 3) or
        ($created_at_yr < $exp_date_yr and ($exp_date_mnth == 12 and $created_at_mnth == 3) or
            ($exp_date_mnth == 11 and $created_at_mnth == 2) or
            ($exp_date_mnth == 10 and $created_at_mnth == 1)
        )
    ){//если expiry_date 3 месяца
        $validity = \common\models\Ads::DATE_RANGE_THREE_MONTHS;
    }
    if(
        ($created_at_yr == $exp_date_yr and $exp_date_mnth - $created_at_mnth == 6) or
        ($created_at_yr < $exp_date_yr and
            ($exp_date_mnth == 12 and $created_at_mnth == 6) or
            ($exp_date_mnth == 11 and $created_at_mnth == 5) or
            ($exp_date_mnth == 10 and $created_at_mnth == 4) or
            ($exp_date_mnth == 9 and $created_at_mnth == 3) or
            ($exp_date_mnth == 8 and $created_at_mnth == 2) or
            ($exp_date_mnth == 7 and $created_at_mnth == 1)
        )
    ){//если expiry_date 6 месяцев
        $validity = \common\models\Ads::DATE_RANGE_SIX_MONTHS;
    }
    //если expiry_date 1 год
    if($exp_date_yr - $created_at_yr == 1 and $created_at_mnth == $exp_date_mnth){
        $validity = \common\models\Ads::DATE_RANGE_ONE_YEAR;
    }
    //если expiry_date 2 года
    if($exp_date_yr - $created_at_yr == 2 and $created_at_mnth == $exp_date_mnth){
        $validity = \common\models\Ads::DATE_RANGE_TWO_YEARS;
    }
    //если expiry_date 3 года
    if($exp_date_yr - $created_at_yr == 3 and $created_at_mnth == $exp_date_mnth){
        $validity = \common\models\Ads::DATE_RANGE_THREE_YEARS;
    }
    //если expiry_date неорганичен
    if($exp_date_yr - $created_at_yr == 20 and $created_at_mnth == $exp_date_mnth){
        $validity = \common\models\Ads::DATE_RANGE_UNLIMITED;
    }
}
$categories = Category::find()
    ->where(['parent_id' => NULL, 'active'=>1])
    ->orderBy('order ASC, brand ASC, techname ASC')
    ->withText(['languages_id' => Language::getDefault()->id])
    ->all();
$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                ['type' => Form::INPUT_CSRF],
                ['name' => 'title','type' => Form::INPUT_TEXT,'label' => 'Заголовок','model'=>$ad],
                ['name' => 'price','type' => Form::INPUT_TEXT,'label'=>'Цена','model' => $ad],
                ['name' => 'text','type' => Form::INPUT_AREA_TEXT,'label' => 'Описание','model'=>$ad, 'model_name' => 'Ads'],
                ['name' => 'placements_id','type' => Form::SELECT, 'options' => \common\models\Placement::getAllForSelect(),'label' => 'Действие','model'=>$ad, 'model_name' => 'Ads'],
                ['name' => 'expiry_date','type' => Form::SELECT, 'options' => $expiry_options,'label' => 'Срок действия','model'=>$ad, 'model_name' => 'Ads', 'selected' => $validity],
                ['name' => 'cities_id','type' => Form::SEARCH_AUTOCOMPLETE,'label' => 'Город', 'model_name' => 'Ads', 'model'=>$ad, 'url' => 'cities/search ', 'placeholder' => 'Начните печатать название города...', 'current_value' => $current_city, 'input_id' => 'cities_id'
                ],
                ['name' => 'categories_ids','type' => Form::INPUT_TREE,'label' => 'Категории', 'model'=>$ad, 'no_cats_message' => "Нет выбранных категорий", 'categories' => $categories],
                ['name' => 'files_id','type' => Form::INPUT_MEDIA,'label' => 'Изображения', 'model'=>$ad, 'no_cats_message' => "Нет выбранных категорий", 'categories' => $categories]
            ]
        ],
    ]
];

echo Form::widget($items);