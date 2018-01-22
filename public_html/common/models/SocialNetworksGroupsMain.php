<?php

namespace common\models;
/**
 * Class SocialNetworksGroupsMain
 * @package common\models
 *
 * @property string $name
 * @property boolean $as_default
 *
 * @property SocialNetworksGroups[] $default_groups
 * @property Category[] $categories
 * @property SocialNetworksGroups[] $sn_groups
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
            ->viaTable('social_networks_groups_main_groups', ['categories_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    function getDefaultGroups(){
        return $this->hasMany(SocialNetworksGroups::className(), ['id' => 'group_id'])
            ->viaTable('social_networks_groups_categories', ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    function getSnGroups(){
        return $this->hasMany(SocialNetworksGroups::className(), ['social_networks_groups_main_id' => 'id']);
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
}