<?php

namespace common\models;

use common\models\CurrencyText;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property string $code
 * @property bool $active
 * @property bool $is_default
 *
 * @property Country[] $countries
 */
class Currency extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'currencies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'active', 'is_default'], 'required'],
            [['code'], 'string'],
            [['active', 'is_default'], 'integer', 'max' => 1],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => \backend\behaviors\SaveRelation::className(),
                'relations' => ['currencyText']
            ],
            [
                'class' => \frontend\behaviors\Multilanguage::className(),
                'relationName' => 'currencyText',
                'relationClassName' => CurrencyText::className(),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => __('Code'),
            'is_default' => __('As default'),
            'active' => __('Activity'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(Country::className(), ['currencies_id' => 'id']);
    }
}
