<?php

namespace common\models;
/**
 * Class SocialNetworksGroups
 * @package common\models
 *
 * @property string $name
 * @property string $url
 * @property string $group_id
 * @property string $token
 * @property string $consumer_key
 * @property string $consumer_secret
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
            [['name', 'url', 'group_id', 'token', 'consumer_key', 'consumer_secret'], 'string', 'max' => 255],
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
            'url' => 'Город',
            'group_id' => 'ID группы',
            'token' => 'Токен',
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

}