<?php

namespace common\models;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $vk_token
 */
class Settings extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vk_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vk_token' => 'VK токен',
        ];
    }

}
