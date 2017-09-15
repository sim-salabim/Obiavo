<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "attributes_predefined_values".
 *
 * @property integer $attributes_id
 * @property string $value
 * @property integer $order
 *
 */
class AttributesPredefinedValues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attributes_predefined_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attributes_id', 'value',], 'required'],
            [['value'], 'string', 'max' => 255],
            [['attributes_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryAttribute::className(), 'targetAttribute' => ['attributes_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attributes_id' => 'Attribute',
            'value' => 'Value',
            'order' => 'Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPredefinedValuesAttribute()
    {
        return $this->hasOne(CategoryAttribute::className(), ['id' => 'attributes_id']);
    }
}
