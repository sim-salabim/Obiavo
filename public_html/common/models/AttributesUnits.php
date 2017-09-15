<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "attributes_units".
 *
 * @property integer $id
 * @property string $value
 * @property integer $attributes_id
 *
 */
class AttributesUnits extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attributes_units';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'attributes_id'], 'required'],
            [['value', ], 'string', 'max' => 100],
            [['attributes_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['attributes_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => \backend\behaviors\SaveRelation::className(),
                'relations' => ['AttributesUnitsText']
            ],
            [
                'class' => \frontend\behaviors\Multilanguage::className(),
                'relationName' => 'attributesUnitsText',
                'relationClassName' => AttributesUnitsText::className(),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'value' => 'Value',
            'attributes_id' => 'Attribute',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryAttribute()
    {
        return $this->hasOne(CategoryAttribute::className(), ['id' => 'attributes_id']);
    }
}
