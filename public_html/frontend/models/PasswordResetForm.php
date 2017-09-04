<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Password recovery form
 */
class PasswordResetForm extends Model
{
    public $pass;
    public $pass_confirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pass', 'pass_confirm'], 'required', 'message' => __('Required field')],
            [['pass'], 'string', 'min' => 6],
            [['pass', 'pass_confirm'], 'validatePass']
        ];
    }
    /**
     * Validates if passes are same.
     * This method serves as the inline validation for email.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePass($attribute, $params)
    {

        if (!$this->hasErrors()) {
            if ($this->pass != $this->pass_confirm) {
                $this->addError($attribute, __('Passwords don\'t match'));
            }
        }
    }
}
