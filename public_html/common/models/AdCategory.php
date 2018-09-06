<?php

namespace common\models;

use common\models\scopes\CategoryQuery;

/**
 * This is the model class for table "ads_has_cateogries".
 *
 * @property integer $id
 * @property integer $categories_id
 * @property integer $ads_id
 *
 * @property Placement $ad
 * @property Category $category
 */
class AdCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ads_has_categories';
    }

    public function behaviors()
    {
        return [
            [
                'class' => \backend\behaviors\SaveRelation::className(),
                'relations' => ['categoriesText']
            ],
            [
                'class' => \frontend\behaviors\Multilanguage::className(),
                'relationName' => 'categoriesText',
                'relationClassName' => CategoriesText::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categories_id', 'ads_id'], 'required'],
            [['categories_id', 'ads_id'], 'integer'],
            [['ads_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ads::className(), 'targetAttribute' => ['ads_id' => 'id']],
            [['categories_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['categories_id' => 'id']],
        ];
    }

    public static function find(){
        return new CategoryQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categories_id' => 'Categories ID',
            'ads_id' => 'Ads ID',
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
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'categories_id']);
    }

}
