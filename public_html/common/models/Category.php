<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use common\models\scopes\CategoryQuery;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $techname
 * @property string $name
 * @property integer $active
 * @property integer $social_networks_groups_main_id
 * @property boolean $brand
 * @property boolean $clean_harakterisitka
 * @property boolean $href
 * @property integer $excel_id
 * @property integer $seo_id
 * @property integer $href_id
 * @property integer $order
 *
 * @property Ads[] $ads
 * @property Category $parent
 * @property Category[] $children
 * @property Placement $placements
 * @property Category[] $categories
 * @property Ads[] $availableAds
 * @property CategoryAttribute[] $categoriesAttributes
 * @property SocialNetworksGroups[] $socialNetworkGroups
 * @property SocialNetworksGroupsMain $socialNetworkGroupsMain
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
                [
                    'class' => \frontend\behaviors\Multilanguage::className(),
                    'relationName' => 'categoriesText',
                    'relationClassName' => CategoriesText::className(),
                ],
            ];
    }

    public static function find(){
        return new CategoryQuery(get_called_class());
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
    public function getAvailableAds()
    {
        return $this->hasOne(Ads::className(), ['id' => 'ads_id'])
            ->viaTable('categories_has_ads', ['categories_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id'])->withText();
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialNetworkGroupsMain()
    {
        return $this->hasOne(SocialNetworksGroupsMain::className(), ['id' => 'main_group_id'])
            ->viaTable('social_networks_groups_main_categories', ['categories_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    function getSocialNetworkGroups(){
        return $this->hasMany(SocialNetworksGroups::className(), ['id' => 'categories_id'])
            ->viaTable('social_networks_groups_categories', ['categories_id' => 'id']);
    }

    public function getChildren()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id'])
                    ->withText();
    }

    public function getCategoriesAttributes()
    {
        return $this->hasMany(CategoriesAttributes::className(), ['categories_id' => 'id']);
    }

    public function getCategoriesTexts()
    {
        return $this->hasMany(CategoriesText::className(), ['categories_id' => 'id']);
    }

    public function getCategoriesText()
    {
        return $this->hasOne(CategoriesText::className(), ['categories_id' => 'id']);
//                    ->where(['languages_id' => Yii::$app->user->getLanguage()->id]);
    }

    public function getAllCategoryChildren(){
        return Category::find()->where(['parent_id'=>$this->id])->all();
    }

    /**
     * Типы объявлений у категорий (купить, продать, аренда)
     */
    public function getPlacements()
    {
        $placements = (new Query())
            ->select('*')
            ->from('placements')
            ->leftJoin('categories_has_placements', 'placements.id = categories_has_placements.placements_id')
            ->leftJoin('placements_text', 'placements.id = placements_text.placements_id')
            ->where(['categories_has_placements.categories_id' => $this->id])
            ->all();
        return $placements ;
    }

    public function getAttributes(){
        return $this->hasMany(CategoryAttribute::className(), ['id' => 'attributes_id'])
            ->viaTable('categories_has_attributes', ['categories_id' => 'id']);
    }

    public function setPlacements($placementsIds) {
        $existing_ids = [];
        foreach($this->placements as $epl){
            $existing_ids[] = $epl['placements_id'];
        }
        $rows_to_insert = [];
        $ids_to_remove = [];
        foreach ($placementsIds as $placeId){
            if(!in_array($placeId, $existing_ids)){
                $rows_to_insert[] = [$this->id, $placeId];
            }
        }
        foreach ($existing_ids as $e_id){
            if(!in_array($e_id, $placementsIds)){
                $ids_to_remove[] = $e_id;
            }
        }
        if(!empty($ids_to_remove)){
            foreach($ids_to_remove as $id_to_remove){
                $cat_to_delete = CategoryPlacement::find()
                    ->where(['categories_id' => $this->id])
                    ->andWhere(['placements_id' => $id_to_remove])
                    ->one();
                if($cat_to_delete){
                    $cat_to_delete->delete();
                }
            }
        }

        Yii::$app->db->createCommand()->batchInsert(
                    CategoryPlacement::tableName(),
                    ['categories_id','placements_id'],
                    $rows_to_insert
                )
                ->execute();
    }

    public function deletePlacements(){
        Yii::$app->db
                ->createCommand()
                ->delete(CategoryPlacement::tableName(), ['=','categories_id', $this->id])
                ->execute();
    }

    /**
     * URL на основе связанной таблицы
     */
    public function getUrl(){
        return yii\helpers\ArrayHelper::getValue($this->categoriesText, 'url','');
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
     *   Рекурсивно перебирает все категории и извлекает их дочерние категории
     *
     * @param array $categories[], Category
     * @param array $result, массив уже извлеченных категорий
     * @return array
     */
    public static function getAllChildren($categories = [], $result = [], $level = 1, $limit = 4){
        $parentIds = ArrayHelper::getColumn($categories, 'id');
        $categories_result = Category::find()
            ->where(['IN','parent_id',$parentIds])
            ->with('categoriesText')
            ->all();
        if(count($categories_result)) {
            $has_kids = false;
            foreach ($categories_result as $row) {
                array_push($result, $row->id);
                if($row->children){
                    $has_kids = true;
                }
            }
            if($has_kids){
                $level++;
                if($level >= $limit){
                    return $result;
                }else{
                    return Category::getAllChildren($categories_result, $result, $level);
                }
            }else{
                return $result;
            }
        }else{
            return $result;
        }
    }

    /**
     * @return string
     */
    public function url(){
        return $this->_text->url.'/';
    }

     /**
     * Найти всех родителей пункта меню
     */
    public function getAllParentsForBreadcrumbs()
    {
        $parent = $this;
        $breadcrumbs = [];

        while ($parent) {
            $breadcrumbs[] = ['label' => $parent->_text->name, 'link' => $parent->_text->url."/", 'use_cookie' => true];
            $parent = $parent->getParent()->one();
        }
        return array_reverse($breadcrumbs);
    }

    /**
     * Найти всех родителей категории  в том числе и текущую
     */
    public function getAllParents()
    {
        $parent = $this;
        $parents = [];
        $parents[] = $parent;
        while ($parent) {
            $parent = $parent->getParent()->one();
            $parents[] = $parent;
        }
        return $parents;
    }

    /**
     * Найти всех родителей категории  кроме текущей
     */
    public function getAllParentsExceptCurrent()
    {
        $parent = $this;
        $parents = [];
        while ($parent) {
            $parent = $parent->getParent()->one();
            if($parent) {
                $parents[] = $parent;
            }
        }
        return $parents;
    }

    /**
     * Найти всех родителей пункта меню
     */
    public function getBreadcrumbs()
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

    public static function getByOldUrlCache($old_url)
    {
        $key  = "Category-getByOldUrlCache-" . md5($old_url);
        $data = Yii::$app->cache->get( $key );

        if ($data === false) {
            $data = self::find()->searchUrlByLanguage($old_url)->one();
            Yii::$app->cache->set( $key, $data, 300 );

            return $data;
        }
        return $data;
    }

    public static function getByUrl($url){
        return self::find()->searchUrlByLanguage($url)->one();
    }

    /** Возвращает основную группу (SocialNetworksGroupsMain) текущей категории, если группы нет, то
     *  пробует найти ее у родительской категории и тд. Если не находит -
     *  возвращает false
     *
     * @return bool|SocialNetworksGroupsMain
     */
    public function getSnMainGroup(){
        if($this->socialNetworkGroupMain){
            return $this->socialNetworkGroupMain;
        }else{
            if($this->parent){
                return $this->parent->getSnMainGroup();
            }else{
                return false;
            }
        }
    }
}
