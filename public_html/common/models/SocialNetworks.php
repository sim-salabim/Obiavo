<?php

namespace common\models;
/**
 * Class SocialNetworks
 * @package common\models
 *
 * @property string $name
 * @property integer $default_group_id
 *
 * @property SocialNetworks $default
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
            [['name'], 'required'],
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
    function getDefault(){
        return $this->hasOne(SocialNetworksGroups::className(), ['default_group_id' => 'id']);
    }
}
