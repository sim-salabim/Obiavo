<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $techname
 * @property integer $active
 *
 * @property Ads[] $ads
 * @property Category $parent
 * @property Category[] $categories
 * @property CategoriesAttributes[] $categoriesAttributes
 * @property CategoryGenerated[] $categoryGenerated
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'active'], 'integer'],
            [['techname'], 'required'],
            [['techname'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'techname' => 'Техническое название',
            'active' => 'Активность',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAds()
    {
        return $this->hasMany(Ads::className(), ['categories_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    public function getCategoriesAttributes()
    {
        return $this->hasMany(CategoriesAttributes::className(), ['categories_id' => 'id']);
    }

    public function getCategoryGenerated()
    {
        return $this->hasMany(CategoryGenerate::className(), ['categories_id' => 'id']);
    }

    /**
     * Вернуть главные категории категории (без родительских категорий)
     */
    public static function getMainCategories(){
        return Category::find()
                    ->where(['parent_id' => null])
                    ->all();
    }

    /**
     * Добавляем запись в связанную таблицу
     */
    public function setCategoryGeneratedRecord($categoryGenerateModel){

        $categoryGenerateModel->countries_id = Yii::$app->user->getDefaultCountry()->id;

        $this->link('categoryGenerated',$categoryGenerateModel);
    }
}
