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
    public $cities_id;
    public $phone_number;
    public $rememberMe = true;
    const PHONE_NUMBER_MIN_LENGTH = 8;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password','cities_id', 'first_name', 'last_name', 'cities_id', 'phone_number'], 'required', 'message' => __('Required field')],
            ['email','email', 'message' => __('Incorrect email')],
            ['email','unique', 'targetClass' => \common\models\User::className(),
                                'message' => __('User already exists')],
            ['rememberMe', 'boolean'],
            ['phone_number', 'validatePhoneNumber'],
            ['password', 'string', 'min' => 6, 'message' => __('Password must be minimum 6 characters long')],
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
            $user->phone_number = $this->phone_number;
            $user->password = $this->password;

            if ($user->validate()){
                $user->save();
                $this->_user = $user;
            }
        }

        return $this->_user;
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validatePhoneNumber($attribute, $params){
        if (!is_numeric($this->phone_number)) {
            $this->addError($attribute, __('Phone number must contain digits only'));
        }
        if(strlen($this->phone_number) < self::PHONE_NUMBER_MIN_LENGTH){
            $this->addError($attribute, __('Min length is ').self::PHONE_NUMBER_MIN_LENGTH);
        }
    }
}
