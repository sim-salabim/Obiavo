<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "categories_attributes".
 *
 * @property integer $id
 * @property integer $attributes_types_id
 * @property string $techname
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
            [['techname'], 'string', 'max' => 255],
            [['attributes_types_id'], 'exist', 'skipOnError' => true, 'targetClass' => AttributesTypes::className(), 'targetAttribute' => ['attributes_types_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attributes_types_id' => 'Attributes Types ID',
            'techname' => 'Techname',
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
    public function getCategoriesAttributesTexts()
    {
        return $this->hasMany(CategoriesAttributesText::className(), ['categories_attributes_id' => 'id']);
    }

    public function getCategories() {
        return $this->hasMany(Category::className(), ['id' => 'categories_id'])
            ->viaTable('categories_has_attributes', ['attributes_id' => 'id']);
    }

    public function getValue(){
        return $this->hasOne(AdsAttributesValues::className(), ['id' => 'ads_id']);
    }
}
