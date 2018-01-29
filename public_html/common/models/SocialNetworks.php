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

    /** Возвращает группу
     * @param Category $category
     * @return array|bool|null|\yii\db\ActiveRecord
     */
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
                        $group = $this->getBlockFromMainGroupByCategory($category);
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
                $group = $this->getBlockFromMainGroupByCategory($category);
            }
        }
        if(!$group){
            $group = $this->getDefaultMainsGroupBlock();
        }
        if(!$group){
            $group = $this->default;
        }
        return $group;
    }

    function getBlockFromMainGroupByCategory(Category $category){
        $main_group = $category->socialNetworkGroupsMain;
        if($main_group){
            $block = SocialNetworksGroupsMain::getBlockBySocialNetworkId($this->id, $main_group->id);
            if($block){
                return $block;
            }else{
                if($category->parent){
                    return $this->getBlockFromMainGroupByCategory($category->parent);
                }else{
                    return false;
                }
            }
        }else{
            if($category->parent){
                return $this->getBlockFromMainGroupByCategory($category->parent);
            }else{
                return false;
            }
        }
    }

    public function getDefaultMainsGroupBlock(){
        return SocialNetworksGroups::find()
            ->select('social_networks_groups.*')
            ->leftJoin('social_networks_groups_main_groups', 'social_networks_groups_main_groups.group_id = social_networks_groups.id')
            ->leftJoin('social_networks_groups_main', 'social_networks_groups_main.id = social_networks_groups_main_groups.main_group_id')
            ->where(['social_networks_groups.social_networks_id'=>$this->id])
            ->one();
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
            ->select('social_networks_groups.*')
            ->where([
                'social_networks_groups.cities_id' => $location->city->id,
                'social_networks_groups.categories_id' => $category->id,
                'social_networks_groups.social_networks_id' => $this->id
            ])
            ->leftJoin('social_networks_groups_categories', 'social_networks_groups_categories.group_id = social_networks_groups.id')
            ->andWhere(['social_networks_groups_categories.categories_id' => $category->id])
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
            ->select('social_networks_groups.*')
            ->where([
                'social_networks_groups.cities_id' => null,
                'social_networks_groups.regions_id' => $location->region->id,
                'social_networks_groups.categories_id' => $category->id,
                'social_networks_groups.social_networks_id' => $this->id
            ])
            ->leftJoin('social_networks_groups_categories', 'social_networks_groups_categories.group_id = social_networks_groups.id')
            ->andWhere(['social_networks_groups_categories.categories_id' => $category->id])
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
            ->select('social_networks_groups.*')
            ->where([
                'social_networks_groups.cities_id' => null,
                'social_networks_groups.regions_id' => null,
                'social_networks_groups.countries_id' => $location->country->id,
                'social_networks_groups.social_networks_id' => $this->id
            ])
            ->leftJoin('social_networks_groups_categories', 'social_networks_groups_categories.group_id = social_networks_groups.id')
            ->andWhere(['social_networks_groups_categories.categories_id' => $category->id])
            ->one();
        if(!empty($group)){
            return $group;
        }else{
            if($category->parent){
                return $this->getBlockByCountryAndCategory($category->parent);
            }else{
                return false;
            }
        }
    }
}
