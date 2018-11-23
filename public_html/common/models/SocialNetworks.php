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

    const VK_COM = 'vk.com';
    const FB_COM = 'facebook.com';
    const OK_RU = 'ok.ru';
    const TWITTER = 'twitter';
    const INSTAGRAM = 'instagram.com';

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
            [['active', 'autoposting'], 'integer', 'max' => 1, 'min' => 0],
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

    /**
     * @param array $keys
     * @return array(key => value, key => value....)
     */
    static function getAllAsArrayForAutoposting($keys = ['id', 'name']){
        $result = [];
        $groups = SocialNetworks::find()
            ->where(['active'=>1,'autoposting'=>1])
            ->all();
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

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    static function getNetworksForAutoposting(){
        return SocialNetworks::find()
            ->where(['autoposting' => 1])
            ->all();
    }

    /** Возвращает группу
     * @param Category|null $category
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function getGroupsBlock($category = null){
        $location = \Yii::$app->location;
        $group = null;
        if($category) {
            if ($category->socialNetworkGroupsMain) {
                if ($location->city) {
                    $group = $this->getBlockByCityAndCategory($category);
                    if (!$group) {
                        if($category->parent){
                            return $this->getGroupsBlock($category->parent);
                        }
                    }
                }
                if ($location->country) {
                    $group = $this->getBlockByCountryAndCategory($category);
                    if (!$group) {
                        if($category->parent){
                            return $this->getGroupsBlock($category->parent);
                        }
                    }
                }
            } else {
                if ($category->parent) {
                    return $this->getGroupsBlock($category->parent);
                }
            }
            if(!$group and $location->city){
                $group = SocialNetworksGroups::find()
                    ->where([
                        "cities_id" => $location->city->id,
                        "social_networks_id" => $this->id,
                        "social_networks_groups_main_id" =>
                            (new \yii\db\Query())->select('id')->from('social_networks_groups_main')->where(['as_default' => 1])
                    ])->one();
            }
            if (!$group AND $category->socialNetworkGroupsMain) {
                $group = $category->socialNetworkGroupsMain->getDefaultGroupBySnId($this->id);
                if (!$group) {
                    $group = $category->socialNetworkGroupsMain->getDefaultGroupBySnIdFromDefault($this->id);
                }
            }
        }
        if(!$group){
            $group = $this->default;
        }
        return $group;
    }

    /**
     *  Возвращает сообщество соцсети $this, для объявления $ad
     *
     * @param Ads $ad, экземпляр класса объявлений
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function getGroupForAutoposting(Ads $ad){
        $group = $this->getAdGroup($ad, $ad->category);
        if(!$group){
            $group = SocialNetworksGroups::find()
                ->where([
                    "cities_id" => $ad->city->id,
                    "social_networks_id" => $this->id,
                    "social_networks_groups_main_id" =>
                        (new \yii\db\Query())->select('id')->from('social_networks_groups_main')->where(['as_default' => 1])
                    ])->one();

        }
        if (!$group AND $ad->category->socialNetworkGroupsMain) {
            $group = $ad->category->socialNetworkGroupsMain->getDefaultGroupBySnId($this->id);
            if (!$group) {
                $group = $ad->category->socialNetworkGroupsMain->getDefaultGroupBySnIdFromDefault($this->id);
            }
        }

        if(!$group){
            $group = $this->default;
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
        $main_group = $category->socialNetworkGroupsMain;
        $group = null;
        if($main_group) {
            $group = SocialNetworksGroups::find()
                ->select('social_networks_groups.*')
                ->where([
                    'social_networks_groups.cities_id' => $location->city->id,
                    'social_networks_groups.regions_id' => $location->city->region->id,
                    'social_networks_groups.countries_id' => $location->city->region->country->id,
                    'social_networks_groups.social_networks_id' => $this->id,
                    'social_networks_groups_main_categories.categories_id' => $category->id
                ])
                ->leftJoin('social_networks_groups_main_categories', 'social_networks_groups_main_categories.main_group_id = social_networks_groups.social_networks_groups_main_id')
                ->one();
        }
        return $group;
    }

    /**
     *      Находим соц сообщество соцсети $this для обьявления $ad
     * @param Ads $ad
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getAdGroup(Ads $ad, Category $category){
        if(!$category->socialNetworkGroupsMain){
            if($category->parent){
                return $this->getAdGroup($ad, $category->parent);
            }
        }
        $group = null;
        $city = City::find()->where(['id'=>$ad->city->id])->one();
        if($category->socialNetworkGroupsMain and $city) {
            $group = $this->getGroup($city, $category);
        }
        if($category->socialNetworkGroupsMain and $city->region->country and !$group){
            $group = $this->getGroupByCountry($city->region->country, $category);
        }
        return $group;
    }

    public function getGroupByCountry(Country $country, Category $category){
        $group = SocialNetworksGroups::find()
            ->select('social_networks_groups.*')
            ->where([
                'social_networks_groups.cities_id' => null,
                'social_networks_groups.regions_id' => null,
                'social_networks_groups.countries_id' => $country->id,
                'social_networks_groups.social_networks_id' => $this->id,
                'social_networks_groups_main_categories.categories_id' => $category->id
            ])
            ->leftJoin('social_networks_groups_main_categories', 'social_networks_groups_main_categories.main_group_id = social_networks_groups.social_networks_groups_main_id')
            ->one();
        if(!$group){
            $group = null;
            if($category->parent){
                return $this->getGroupByCountry($country, $category->parent);
            }
        }
        return $group;
    }

    /**
     *      Находим сообщество соцсети $this по городу $city и категории $category
     *  функция рекурсивная, если не находит для предоставленной категории, то ищет для ее родительской,
     *  если не находит ни у одной родительской, то возвращает
     * @param City $city
     * @param Category $category
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getGroup(City $city, Category $category){
        $group = SocialNetworksGroups::find()
            ->select('social_networks_groups.*')
            ->where([
                'social_networks_groups.cities_id' => $city->id,
                'social_networks_groups.regions_id' => $city->region->id,
                'social_networks_groups.countries_id' => $city->region->country->id,
                'social_networks_groups.social_networks_id' => $this->id,
                'social_networks_groups_main_categories.categories_id' => $category->id
            ])
            ->leftJoin('social_networks_groups_main_categories', 'social_networks_groups_main_categories.main_group_id = social_networks_groups.social_networks_groups_main_id')
            ->one();
        if(!$group){
            $group = null;
            if($category->parent){
                return $this->getGroup($city, $category->parent);
            }
        }
        return $group;
    }
    /** Проверяет есть ли соцгруппы у полученой категории для региона локации, если нет, то смотрит родительскую и тд
     *  если ничего не найдено возвращает false
     *
     * @param Category $category
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function getBlockByRegionAndCategory(Category $category){
        $location = Yii::$app->location;
        $main_group = $category->socialNetworkGroupsMain;
        $group = null;
        if($main_group) {
            $group = SocialNetworksGroups::find()
                ->select('social_networks_groups.*')
                ->where([
                    'social_networks_groups.cities_id' => null,
                    'social_networks_groups.regions_id' => $location->region->id,
                    'social_networks_groups.countries_id' => $location->region->country->id,
                    'social_networks_groups.social_networks_id' => $this->id,
                    'social_networks_groups_main_categories.categories_id' => $category->id
                ])
                ->leftJoin('social_networks_groups_main_categories', 'social_networks_groups_main_categories.main_group_id = social_networks_groups.social_networks_groups_main_id')
                ->one();
        }
        return $group;
    }

    /** Проверяет есть ли соцгруппы у полученой категории для страны локации, если нет, то смотрит родительскую и тд
     *  если ничего не найдено возвращает false
     *
     * @param Category $category
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function getBlockByCountryAndCategory(Category $category){
        $location = Yii::$app->location;
        $main_group = $category->socialNetworkGroupsMain;
        $group = null;
        if($main_group){
            $group = SocialNetworksGroups::find()
                ->select('social_networks_groups.*')
                ->where([
                    'social_networks_groups.cities_id' => null,
                    'social_networks_groups.regions_id' => null,
                    'social_networks_groups.countries_id' => $location->country->id,
                    'social_networks_groups.social_networks_id' => $this->id,
                    'social_networks_groups_main_categories.categories_id' => $category->id
                ])
                ->leftJoin('social_networks_groups_main_categories', 'social_networks_groups_main_categories.main_group_id = social_networks_groups.social_networks_groups_main_id')
                ->one();
        }
        return $group;
    }

    /**
     * Извлекает текущую задачу автопостинга для соцсети в соответствии с установленым проиритетом
     * @return array|null|\yii\db\ActiveRecord
     */
    function getActiveAutopostingTask(){
        return AutopostingTasks::find()
            ->select('autoposting_tasks.*')
            ->leftJoin('social_networks_groups', 'social_networks_groups.id = autoposting_tasks.social_networks_groups_id')
            ->leftJoin('social_networks', 'social_networks.id = social_networks_groups.social_networks_id')
            ->where(['autoposting_tasks.status' => AutopostingTasks::STATUS_PENDING, 'social_networks.id' => $this->id])
            ->orderBy('autoposting_tasks.priority DESC, autoposting_tasks.created_at ASC')
            ->one();
    }
}
