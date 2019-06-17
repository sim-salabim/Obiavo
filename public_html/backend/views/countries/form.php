<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use backend\widgets\Form;
use yii\helpers\ArrayHelper;

$languages = common\models\Language::find()->all();
$currencies = \common\models\Currency::find()->where(['active' => 1])->all();
$lang = $country->language ? $country->language : new common\models\Language;
$currency = $country->currency ?:  \common\models\Currency::find()->where(['is_default' => 1])->one();

$items = [
    'saveUrl' => $toUrl,
    'rows' => [
        [
            'panel-title' => 'Основныe данные',
            'attributes' => [
                ['name' => 'domain','type' => Form::INPUT_TEXT,'label' => 'Домен','model'=>$country],
                ['name' => 'active','type' => Form::INPUT_CHECKBOX_INACTIVE,'label'=>'активность','model' => $country],
                [
                    'name' => 'Country[languages_id]',
                    'type' => Form::MULTISELECT,
                    'label'=>'Основной язык',
                    'model' => $country,

                    'selectpicker' => [
                      'values' => ArrayHelper::map($languages, 'id','techname'),
                      'selected' => ArrayHelper::getValue($lang,'id'),
                       'options' => [
                           'id' => 'country-languages_id'
                       ]
                    ]
                ],
                [
                    'name' => 'Country[currencies_id]',
                    'type' => Form::MULTISELECT,
                    'label'=>'Валюта',
                    'model' => $country,

                    'selectpicker' => [
                      'values' => ArrayHelper::map($currencies, 'id','code'),
                      'selected' => ArrayHelper::getValue($currency,'id'),
                       'options' => [
                           'id' => 'country-currencies_id'
                       ]
                    ]
                ],
            ]
        ],
        [
            'panel-title' => 'Тексты',
            'attributes' => [
                ['name' => 'name','type' => Form::INPUT_TEXT,'label' => 'Название','model'=>$country->_text],
                ['name' => 'name_rp','type' => Form::INPUT_TEXT,'label' => 'Название в родительном падеже','model'=>$country->_text],
                ['name' => 'name_pp','type' => Form::INPUT_TEXT,'label' => 'Название в предложном падеже','model'=>$country->_text],
            ]
        ],
    ]
];

echo Form::widget($items);