<?php

namespace common\models;

use common\models\scopes\CategoryPlacementQuery;
use common\models\scopes\PlacementCategoryQuery;
use Yii;

/**
 * This is the model class for table "categories_has_placements".
 *
 * @property integer $id
 * @property integer $categories_id
 * @property integer $placements_id
 *
 * @property Placement $placement
 * @property Category $category
* @property CategoryPlacementText[] $categoriesPlacementText
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

    public function behaviors()
    {
        return [
            [
                'class' => \backend\behaviors\SaveRelation::className(),
                'relations' => ['categoriesPlacementText']
            ],
            [
                'class' => \frontend\behaviors\Multilanguage::className(),
                'relationName' => 'categoriesPlacementText',
                'relationClassName' => CategoryPlacementText::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categories_id', 'placements_id'], 'required'],
            [['categories_id', 'placements_id'], 'integer'],
            [['placements_id'], 'exist', 'skipOnError' => true, 'targetClass' => Placement::className(), 'targetAttribute' => ['placements_id' => 'id']],
            [['categories_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['categories_id' => 'id']],
        ];
    }

    public static function find(){
        return new CategoryPlacementQuery(get_called_class());
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
    public function getPlacement()
    {
        return $this->hasOne(Placement::className(), ['id' => 'placements_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'categories_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesPlacementTexts()
    {
        return $this->hasMany(CategoryPlacementText::className(), ['category_placement_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesPlacementText()
    {
        return $this->hasOne(CategoryPlacementText::className(), ['category_placement_id' => 'id']);
    }
}
