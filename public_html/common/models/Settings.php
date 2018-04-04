<?php

namespace common\models;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $vk_token
 * @property string $fb_token
 * @property string $ok_token
 * @property string $ok_public_key
 * @property string $ok_secret_key
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
            [['vk_token', 'fb_token', 'ok_token', 'ok_public_key','ok_secret_key'], 'string', 'max' => 255],
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
