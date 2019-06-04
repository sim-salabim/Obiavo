<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "countries_text".
 *
 * @property integer $id
 * @property integer $languages_id
 * @property integer $currencies_id
 * @property string $name
 * @property string $name_short
 * @property string $name_rp
 * @property string $name_pp
 * @property string $name_m
 * @property string $name_m_pp
 * @property string $name_m_rp
 *
 * @property Currency $currency
 * @property Language $language
 */
use common\models\Language;
use common\models\Currency;

class CurrencyText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currencies_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currencies_id', 'languages_id', 'name'], 'required'],
            [['currencies_id', 'languages_id',], 'integer'],
            [['name', 'name_rp', 'name_pp', 'name_m', 'name_m_rp', 'name_m_pp'], 'string', 'max' => 255],
            [['name_short'], 'string', 'max' => 5],
            [['languages_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['languages_id' => 'id']],
            [['languages_id'],'default', 'value' => Language::getDefault()->id],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'currencies_id' => __('Currency'),
            'languages_id' => __('Language'),
            'name' => __('Name')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currencies_id']);
    }

    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'languages_id']);
    }
}
