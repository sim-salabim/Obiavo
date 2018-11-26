<?php

namespace common\models;
/**
 * Class SocialNetworksGroupsMain
 * @package common\models
 *
 * @property string $name
 * @property boolean $as_default
 *
 * @property SocialNetworksGroups[] $defaultGroups
 * @property Category[] $categories
 * @property SocialNetworksGroups[] $snGroups
 */
class SocialNetworksGroupsMain extends \yii\db\ActiveRecord
{
    static function tableName()
    {
        return 'social_networks_groups_main';
    }

    /**
     * @inheritdoc
     */
    function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['name'], 'required',],
            [['as_default'], 'boolean',],
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
            'as_default' => 'Использовать по умолчанию',
        ];
    }
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
    function getCategories(){
        return $this->hasMany(Category::className(), ['id' => 'categories_id'])
            ->viaTable('social_networks_groups_main_categories', ['main_group_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    function getDefaultGroups(){
        return $this->hasMany(SocialNetworksGroups::className(), ['id' => 'group_id'])
            ->viaTable('social_networks_groups_main_groups', ['main_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    function getSnGroups(){
        return $this->hasMany(SocialNetworksGroups::className(), ['social_networks_groups_main_id' => 'id']);
    }

    /**
     * Возвращает соц группу для SocialNetworks->id $sn_id и SocialNetworksGroupsMain->id $main_group_id
     * @param $sn_id, SocialNetworks->id
     * @param $main_group_id, SocialNetworksGroupsMain->id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getBlockBySocialNetworkId($sn_id, $main_group_id){
        return SocialNetworksGroups::find()
            ->select('social_networks_groups.*')
            ->leftJoin('social_networks_groups_main_groups', 'social_networks_groups_main_groups.main_group_id = social_networks_groups_main.id')
            ->leftJoin('social_networks_groups', 'social_networks_groups.id = social_networks_groups_main_groups.group_id')
            ->where(['social_networks_groups_main.id' => $main_group_id])
            ->andWhere(['social_networks_groups.social_networks_id' => $sn_id])
            ->one();
    }
    /**
     * @param array $keys
     * @return array(key => value, key => value....)
     */
    static function getAllAsArray($keys = ['id', 'name']){
        $result = [];
        $groups = SocialNetworksGroupsMain::find()->all();
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
     * @param $sn_id
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    function getDefaultGroupBySnId($sn_id){
        $group = SocialNetworksGroups::find()
            ->where([
                'social_networks_groups.social_networks_id' => $sn_id,
                'social_networks_groups_main_groups.main_group_id' => $this->id
            ])
            ->leftJoin('social_networks_groups_main_groups', 'social_networks_groups.id = social_networks_groups_main_groups.group_id')
            ->leftJoin('social_networks_groups_main', 'social_networks_groups_main_groups.main_group_id = social_networks_groups_main.id')
            ->one();
        return $group ? $group : false;
    }

    /**
     * @param $sn_id
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    function getDefaultGroupBySnIdFromDefault($sn_id){
        $country = \Yii::$app->location->country;
        $group = SocialNetworksGroups::find()
            ->where([
                'social_networks_groups.social_networks_id' => $sn_id,
                'social_networks_groups.countries_id' => $country->id,
                'social_networks_groups.regions_id' => null,
                'social_networks_groups.cities_id' => null,
                'social_networks_groups.social_networks_groups_main_id' =>  (new \yii\db\Query())->select('id')->from('social_networks_groups_main')->where(['as_default' => 1])
            ])
            ->one();
        return $group ? $group : false;
    }
}