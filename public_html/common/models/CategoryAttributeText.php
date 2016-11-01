<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "categories_attributes_text".
 *
 * @property integer $id
 * @property integer $categories_attributes_id
 * @property integer $languages_id
 * @property string $text
 *
 * @property Languages $languages
 * @property CategoriesAttributes $categoriesAttributes
 */
class CategoryAttributeText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories_attributes_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categories_attributes_id', 'languages_id', 'text'], 'required'],
            [['categories_attributes_id', 'languages_id'], 'integer'],
            [['text'], 'string', 'max' => 255],
            [['languages_id'], 'exist', 'skipOnError' => true, 'targetClass' => Languages::className(), 'targetAttribute' => ['languages_id' => 'id']],
            [['categories_attributes_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoriesAttributes::className(), 'targetAttribute' => ['categories_attributes_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categories_attributes_id' => 'Categories Attributes ID',
            'languages_id' => 'Languages ID',
            'text' => 'Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasOne(Languages::className(), ['id' => 'languages_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesAttributes()
    {
        return $this->hasOne(CategoriesAttributes::className(), ['id' => 'categories_attributes_id']);
    }
}
