<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
/**
 * New Add form
 */
class SettingsForm extends Model
{
    public $phone_number;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone_number'], 'required', 'message' => __('Required field')],
        ];
    }

    /**
     *  меняет настройки пользователя в личном кабинете
     */
    public function changeSettings(){
        $user = Yii::$app->user->getIdentity();
        if ($this->phone_number[0] == '+') {
            $this->phone_number = cutText($this->phone_number, 16, false);
        }else{
            $this->phone_number = cutText($this->phone_number, 15, false);
        }
        $user->phone_number = $this->phone_number;
        $user->save();
    }
}
