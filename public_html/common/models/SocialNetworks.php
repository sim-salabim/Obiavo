<?php
namespace common\models;

use Yii;
/**
 * Class SocialNetworks
 * @package common\models
 *
 * @property string $name
 * @property integer $default_group_id
 * @property boolean $active
 *
 * @property SocialNetworksGroups $default
 */
class SocialNetworks extends \yii\db\ActiveRecord
{

    static function tableName()
    {
        return 'social_networks';
    }

    /**
     * @inheritdoc
     */
    function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['active'], 'integer', 'max' => 1, 'min' => 0],
            [['name', 'default_group_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'active' => 'Активность',
            'default_group_id' => 'Группа по умолчанию',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => \backend\behaviors\SaveData::className()
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    function getDefault(){
        return $this->hasOne(SocialNetworksGroups::className(), ['id' => 'default_group_id']);
    }

    /**
     * @param array $keys
     * @return array(key => value, key => value....)
     */
    static function getAllAsArray($keys = ['id', 'name']){
        $result = [];
        $groups = SocialNetworks::find()->all();
        if(!empty($groups)){
            foreach($groups as $k => $group){
                foreach($keys as $key){
                    if(isset($group->{$key})) {
                        $result[$k][$key] = $group->{$key};
                    }
                }
            }
        }
        return $result;
    }

    public function getGroupsBlock(Category $category){
        $location = \Yii::$app->location;
        $group = null;
        if($location->city){
            $group = $this->getBlockByCityAndCategory($category);
            if(!$group){
                $group = $this->getBlockByRegionAndCategory($category);
                if(!$group){
                    $group = $this->getBlockByCountryAndCategory($category);
                    if(!$group){
                        $group = $this->default;
                    }
                }
            }
        }else if(!$location->city and $location->region){
            if($location->region){
                $group = $this->getBlockByRegionAndCategory($category);
                if(!$group){
                    $group = $this->getBlockByCountryAndCategory($category);
                }else{
                    $group = $this->default;
                }
            }else{
                $group = $this->default;
            }
        }else if(!$location->city and !$location->region and $location->country){
            $group = $this->getBlockByCountryAndCategory($category);
            if(!$group){
                $group = $this->default;
            }
        }
        return $group;
    }

    /** Проверяет есть ли соцгруппы у полученой категории для города локации, если нет, то смотрит родительскую и тд
     *  если ничего не найдено возвращает false
     *
     * @param Category $category
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function getBlockByCityAndCategory(Category $category){
        $location = Yii::$app->location;
        $group = SocialNetworksGroups::find()
            ->where([
                'cities_id' => $location->city->id,
                'categories_id' => $category->id,
                'social_networks_id' => $this->id
            ])
            ->one();
        if(!empty($group)){
            return $group;
        }else{
            if($category->parent){
                return $this->getBlockByCityAndCategory($category->parent);
            }else{
                return false;
            }
        }
    }
    /** Проверяет есть ли соцгруппы у полученой категории для региона локации, если нет, то смотрит родительскую и тд
     *  если ничего не найдено возвращает false
     *
     * @param Category $category
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function getBlockByRegionAndCategory(Category $category){
        $location = Yii::$app->location;
        $group = SocialNetworksGroups::find()
            ->where([
                'cities_id' => null,
                'regions_id' => $location->region->id,
                'categories_id' => $category->id,
                'social_networks_id' => $this->id
            ])
            ->one();
        if(!empty($group)){
            return $group;
        }else{
            if($category->parent){
                return $this->getBlockByCityAndCategory($category->parent);
            }else{
                return false;
            }
        }
    }

    /** Проверяет есть ли соцгруппы у полученой категории для страны локации, если нет, то смотрит родительскую и тд
     *  если ничего не найдено возвращает false
     *
     * @param Category $category
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function getBlockByCountryAndCategory(Category $category){
        $location = Yii::$app->location;
        $group = SocialNetworksGroups::find()
            ->where([
                'cities_id' => null,
                'regions_id' => null,
                'countries_id' => $location->country->id,
                'categories_id' => $category->id,
                'social_networks_id' => $this->id
            ])
            ->one();
        if(!empty($group)){
            return $group;
        }else{
            if($category->parent){
                return $this->getBlockByCityAndCategory($category->parent);
            }else{
                return false;
            }
        }
    }
}
