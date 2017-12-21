<?php

namespace common\models;
/**
 * Class SocialNetworks
 * @package common\models
 *
 * @property string $name
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
}
