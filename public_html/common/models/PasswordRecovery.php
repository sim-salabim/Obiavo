<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property integer $users_id
 * @property boolean $recovered
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Users $users
 */

class PasswordRecovery extends ActiveRecord
{

    const RECOVERED = 1;
    const NOT_RECOVERED = 0;

    public static function tableName()
    {
        return '{{password_recovery}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [['users_id', 'hash',], 'required'],
            [['users_id', 'created_at', 'updated_at'], 'integer'],
            [['hash'], 'string', 'max' => 255],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['users_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'users_id' => 'Users ID',
            'hash' => 'Hash',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds entity by hasg
     *
     * @param string $hash
     * @return static|null
     */
    public static function findByHash($hash)
    {
        return static::findOne(['hash' => $hash]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'users_id']);
    }
}
