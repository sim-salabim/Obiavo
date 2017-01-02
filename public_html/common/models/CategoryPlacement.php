<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "categories_has_placements".
 *
 * @property integer $id
 * @property integer $categories_id
 * @property integer $placements_id
 *
 * @property Placements $placements
 * @property Categories $categories
 */
class CategoryPlacement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories_has_placements';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categories_id', 'placements_id'], 'required'],
            [['categories_id', 'placements_id'], 'integer'],
            [['placements_id'], 'exist', 'skipOnError' => true, 'targetClass' => Placements::className(), 'targetAttribute' => ['placements_id' => 'id']],
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
            'placements_id' => 'Placements ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacements()
    {
        return $this->hasOne(Placements::className(), ['id' => 'placements_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasOne(Categories::className(), ['id' => 'categories_id']);
    }
}
