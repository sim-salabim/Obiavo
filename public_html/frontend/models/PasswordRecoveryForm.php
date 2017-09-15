<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password recovery form
 */
class PasswordRecoveryForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'required', 'message' => __('Required field')],
            ['email','email', 'message' => __('Incorrect email')],
            ['email', 'validateEmail']
        ];
    }
    /**
     * Validates the email.
     * This method serves as the inline validation for email.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateEmail($attribute, $params)
    {

        if (!$this->hasErrors()) {
            $user = User::findByEmail($this->email);
            if (!$user) {
                $this->addError($attribute, 'Пользователь с данным емейлом не найден.');
            }
        }
    }
}
