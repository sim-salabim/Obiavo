<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "attributes_types".
 *
 * @property integer $id
 * @property string $alias
 * @property string $description
 *
 * @property CategoriesAttributes[] $categoriesAttributes
 */
class AttributeType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attributes_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'description'], 'required'],
            [['alias'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Уникальное имя для типа ',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesAttributes()
    {
        return $this->hasMany(CategoriesAttributes::className(), ['attributes_types_id' => 'id']);
    }
}
