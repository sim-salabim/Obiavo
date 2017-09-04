<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property integer $cities_id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $patronymic
 * @property string $sex
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Ads[] $ads
 * @property Cities $cities
 * @property UsersMessages[] $usersMessages
 * @property UsersMessages[] $usersMessages0
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cities_id', 'email', 'password', 'first_name', 'last_name', 'patronymic', 'created_at', 'updated_at'], 'required',
                'message' => __('Field required')],
            [['cities_id', 'created_at', 'updated_at'], 'integer'],
            [['sex'], 'string'],
            [['email'], 'string', 'max' => 100],
            [['password', 'first_name', 'last_name', 'patronymic'], 'string', 'max' => 255],
            [['cities_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['cities_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cities_id' => 'Cities ID',
            'email' => 'Email',
            'password' => 'Password',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'patronymic' => 'Patronymic',
            'sex' => 'Sex',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAds()
    {
        return $this->hasMany(Ads::className(), ['users_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasOne(Cities::className(), ['id' => 'cities_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersMessages()
    {
        return $this->hasMany(UsersMessages::className(), ['to_users_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersMessages0()
    {
        return $this->hasMany(UsersMessages::className(), ['from_users_id' => 'id']);
    }
}
