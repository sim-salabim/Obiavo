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
 * @property integer $social_networks_id
 * @property integer $social_networks_groups_main_id
 *
 * @property City $city
 * @property Region $region
 * @property SocialNetworks $socialNetwork
 * @property SocialNetworksGroupsMain $socialNetworksGroupMain
 */
class SocialNetworksGroups extends \yii\db\ActiveRecord
{
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
            [['name', 'code_md', 'code_sm', 'code_lg'], 'required'],
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
        ];
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
    function getRegion(){
        return $this->hasOne(Region::className(), ['id' => 'regions_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    function getSocialNetwork(){
        return $this->hasOne(SocialNetworks::className(), ['social_networks_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    function getSocialNetworksGroupMain(){
        return $this->hasOne(SocialNetworksGroupsMain::className(), ['id' => 'social_networks_groups_main_id']);
    }
}