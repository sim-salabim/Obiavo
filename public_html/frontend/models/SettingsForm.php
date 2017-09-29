<?php
namespace frontend\models;

use common\models\Ads;
use common\models\Placement;
use Yii;
use yii\base\Model;
use common\models\Mailer;
use common\models\Files;

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
        $user->phone_number = $this->phone_number;
        $user->save();
    }
}
