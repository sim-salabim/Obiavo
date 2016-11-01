<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "categories_attributes".
 *
 * @property integer $id
 * @property integer $categories_id
 * @property integer $attributes_types_id
 * @property string $techname
 * @property integer $active
 *
 * @property AdsAttributesValue[] $adsAttributesValues
 * @property AttributesTypes $attributesTypes
 * @property Categories $categories
 * @property CategoriesAttributesText[] $categoriesAttributesTexts
 */
class CategoryAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories_attributes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categories_id', 'attributes_types_id', 'techname'], 'required'],
            [['categories_id', 'attributes_types_id', 'active'], 'integer'],
            [['techname'], 'string', 'max' => 255],
            [['attributes_types_id'], 'exist', 'skipOnError' => true, 'targetClass' => AttributesTypes::className(), 'targetAttribute' => ['attributes_types_id' => 'id']],
            [['categories_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['categories_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categories_id' => 'Categories ID',
            'attributes_types_id' => 'Attributes Types ID',
            'techname' => 'Techname',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdsAttributesValues()
    {
        return $this->hasMany(AdsAttributesValue::className(), ['categories_attributes_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributesTypes()
    {
        return $this->hasOne(AttributesTypes::className(), ['id' => 'attributes_types_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasOne(Categories::className(), ['id' => 'categories_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesAttributesTexts()
    {
        return $this->hasMany(CategoriesAttributesText::className(), ['categories_attributes_id' => 'id']);
    }
}
