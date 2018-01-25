<?php

namespace common\models;
/**
 * Class SocialNetworksGroups
 * @package common\models
 *
 * @property string $name
 * @property string $code_sm
 * @property string $code_md
 * @property string $code_lg
 * @property integer $cities_id
 * @property integer $regions_id
 * @property integer $countries_id
 * @property integer $social_networks_id
 * @property integer $social_networks_groups_main_id
 *
 * @property Country $country
 * @property City $city
 * @property Region $region
 * @property Category[] $categories
 * @property SocialNetworks $socialNetwork
 * @property SocialNetworksGroupsMain $socialNetworksGroupMain
 * @property SocialNetworksGroupsMain[] $socialNetworksGroupMainDefault
 */
class SocialNetworksGroups extends \yii\db\ActiveRecord
{
    private $available_networks = ['vk.com', 'facebook.com', 'ok.ru'];

    static function tableName()
    {
        return 'social_networks_groups';
    }

    /**
     * @inheritdoc
     */
    function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['name', 'code_sm', 'social_networks_groups_main_id', 'social_networks_id'], 'required'],
            [['social_networks_groups_main_id', 'social_networks_id', 'cities_id', 'regions_id'], 'integer'],
            [['code_md', 'code_sm', 'code_lg'], 'string'],
            [['countries_id'], 'validateLocation', 'skipOnEmpty' => false, 'skipOnError' => false]
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
     * @inheritdoc
     */
    function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'social_networks_groups_main_id' => 'Основная група',
            'social_networks_id' => 'Соцсеть',
            'countries_id' => 'Страна',
            'regions_id' => 'Регион',
            'cities_id' => 'Город',
            'code_lg' => 'Большой блок',
            'code_md' => 'Средний блок',
            'code_sm' => 'Маленький блок',
        ];
    }

    public function validateLocation($attribute,$params, $validator){
        if(!$this->countries_id and !$this->cities_id and !$this->regions_id){
            $this->addError('countries_id','Необходимо заполнить хотя бы поле "Страна"');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    function getCity(){
        return $this->hasOne(City::className(), ['id' => 'cities_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    function getCountry(){
        return $this->hasOne(Country::className(), ['id' => 'countries_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    function getRegion(){
        return $this->hasOne(Region::className(), ['id' => 'regions_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    function getSocialNetwork(){
        return $this->hasOne(SocialNetworks::className(), ['id' => 'social_networks_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    function getSocialNetworksGroupMain(){
        return $this->hasOne(SocialNetworksGroupsMain::className(), ['id' => 'social_networks_groups_main_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    function getSocialNetworksGroupMainDefault(){
        return $this->hasMany(SocialNetworksGroupsMain::className(), ['id' => 'main_group_id'])
            ->viaTable('social_networks_groups_main_groups', ['main_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    function getCategories(){
        return $this->hasMany(Category::className(), ['id' => 'categories_id'])
            ->viaTable('social_networks_groups_categories', ['group_id' => 'id']);
    }

    /** wozwra]aet
     *
     * @param $city_id
     * @param Category $category
     * @return null
     */
    public function getGroupsByCity($city_id, Category $category){
        $main_group = $category->getSnMainGroup();
        $groups = null;
        if($main_group){
            $groups = SocialNetworksGroups::find()
                ->where('social_networks_groups_main_id', '=', $main_group->id)
                ->andWhere('cities_id', '=', $city_id)
                ->andWhere('social_networks_id', 'NOT IN', (new \yii\db\Query())->select('id')->from('social_networks')->where('name', 'IN', $this->available_networks))
                ->find();
        }
        return $groups;
    }

    public static function getSocialNetworksGroupsBlocks(Category $current_category){
//        $location = Yii::$app->location;
//        if
    }
}