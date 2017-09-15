<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ads_attributes_value".
 *
 * @property integer $id
 * @property integer $ads_id
 * @property integer $categories_attributes_id
 * @property string $value
 *
 */
class AdsAttributesValues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ads_attributes_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ads_id', 'categories_attributes_id',], 'required'],
            [['value', ], 'string', 'max' => 100],
            [['ads_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ads::className(), 'targetAttribute' => ['ads_id' => 'id']],
            [['categories_attributes_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryAttribute::className(), 'targetAttribute' => ['categories_attributes_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ads_id' => 'Ad',
            'categories_attributes_id' => 'Attribute',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAd()
    {
        return $this->hasOne(Ads::className(), ['id' => 'ads_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryAttribute()
    {
        return $this->hasOne(CategoryAttribute::className(), ['id' => 'categories_attributes_id']);
    }

}
