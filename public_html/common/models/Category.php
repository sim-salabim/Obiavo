<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

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

    public function transactions() {
        return [
            // scenario name => operation (insert, update or delete)
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
            ];
    }

    public function behaviors()
    {
            return [
                [
                    'class' => \backend\behaviors\SaveRelation::className(),
                    'relations' => ['categoriesText']
                ],
            ];
    }

    /**
     * Сохраняем связанные модели через populateRecord
     * @param type $insert
     */
//    public function beforeSave($insert) {
//
//        if (!parent::beforeSave($insert)) {
//            return false;
//        }
//
//        $relateModels = $this->getRelatedRecords();
//        foreach ($relateModels as $relateName => $model) {
//
//            if ($model->scenario === self::SCENARIO_BEFORESAVE)
//                $this->link($relateName, $model);
//        }
//
//        return true;
//    }

//    public function afterSave($insert, $changedAttributes) {
//
//        parent::afterSave($insert, $changedAttributes);
//
//        $relateModels = $this->getRelatedRecords();
//        foreach ($relateModels as $relateName => $model) {
////            var_dump(new CategoryGenerate);
////                var_dump($this->getRelation('categoryGenerated'));die;
//
//                $this->link('categoryGenerated', $model);
////            }
//        }
//
//        return true;
//    }

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

    public function getChildrens()
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

    public function getCategoriesTexts()
    {
        return $this->hasMany(CategoriesText::className(), ['categories_id' => 'id']);
    }

    public function getCategoriesText()
    {
        return $this->hasOne(CategoriesText::className(), ['categories_id' => 'id'])
                    ->where(['languages_id' => Yii::$app->user->getLanguage()->id]);
    }

    /**
     * Вернуть главные категории категории (без родительских категорий)
     */
    public static function getMainCategories(){
        return Category::find()
                    ->with('categoriesText')
                    ->where(['parent_id' => null])
                    ->all();
    }

    /**
     * Получить дочерние категории
     * @param array $categories Массив объектов категорий чьих потомков надо получить
     */
    public static function getNextChilds($categories = []){
        $parentIds = ArrayHelper::getColumn($categories, 'id');

        return Category::find()
                    ->where(['IN','parent_id',$parentIds])
                    ->with('categoriesText')
                    ->all();
    }

    /**
     *
     * Сохраняем новый пункт и Добавляем запись в связанную таблицу генераций
     *
     *
     * $model->populateRelation('relationName', $relateModel);
     *  - добавит связанную модель к модели ($model) в массив связей данной модели, которые можно получить $model->getRelationRecords()
     */
    public function saveAndSetRelateForCategoryGenerated($categoryGenerateModel){
        $categoryGenerateModel->countries_id = Yii::$app->user->getDefaultCountry()->id;

        $this->save();

        $this->link('categoryGenerated', $categoryGenerateModel);
    }

     /**
     * Найти всех родителей пункта меню
     */
    public function getAllParentsForBreadcrumbs()
    {
        $parent = $this;
        $breadcrumbs = [];

        while ($parent) {
            $breadcrumbs[] = $parent;

            $parent = $parent->getParent()->one();
        }

        $breadcrumbs = array_reverse($breadcrumbs);

        return ArrayHelper::index($breadcrumbs, 'techname');
    }

    /**
     * Сохраняем новый пункт категорий
     * @param array $data
     */
    public function saveNewData($data){
        $categoryGenerate = new \common\models\CategoriesText;

        $this->load($data);
        $categoryGenerate->load($data);

        $this->saveAndSetRelateForCategoryrated($categoryGenerate);
    }

    /**
     * Обновляем пункт категорий
     * @param array $data
     */
    public function saveUpdateData($data){
        $categoryGenerate = $this->getCategoryGenerated()->one();

        $categoryGenerate->load($data);
        $this->load($data);

        $this->saveAndSetRelateForCategoryGenerated($categoryGenerate);
    }
}
