<?php

namespace common\models;
/**
 * Class SocialNetworksGroupsMain
 * @package common\models
 *
 * @property string $name
 * @property Category $category
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
    function getCategory(){
        return $this->hasOne(Category::className(), ['id' => 'social_networks_groups_main_id']);
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