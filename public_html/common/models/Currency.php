<?php

namespace common\models;

use common\models\scopes\CurrencyQuery;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property string $code
 * @property integer $countries_id
 * @property bool $active
 * @property bool $is_default
 *
 * @property Country $country
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
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'countries_id']);
    }

    public static function find(){
        return new CurrencyQuery(get_called_class());
    }
    public function transactions() {
        return [
            // scenario name => operation (insert, update or delete)
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencyText()
    {
        return $this->hasOne(CurrencyText::className(), ['currencies_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencyTexts()
    {
        return $this->hasMany(CurrencyText::className(), ['currencies_id' => 'id']);
    }
}
