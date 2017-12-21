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
    function getCategory(){
        return $this->hasOne(Category::className(), ['id' => 'social_networks_groups_main_id']);
    }
}