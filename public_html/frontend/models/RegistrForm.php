<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Login form
 */
class RegistrForm extends Model
{
    public $email;
    public $password;
    public $first_name = '';
    public $last_name = '';
    public $city;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password','city'], 'required'],
            ['email','email'],
            ['email','unique', 'targetClass' => \common\models\User::className(),
                                'message' => __('User already exists')],
            ['rememberMe', 'boolean'],
//            ['password', 'validatePassword'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $user = new User;
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->cities_id = $this->city;
            $user->email = $this->email;
            $user->password = $this->password;

            if ($user->validate()){
                $user->save();
                $this->_user = $user;
            }
        }

        return $this->_user;
    }
}
