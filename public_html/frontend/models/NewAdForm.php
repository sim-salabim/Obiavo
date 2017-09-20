<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * New Add form
 */
class NewAdForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'subcategory', 'place', 'time', 'header', 'text', 'price'], 'required', 'message' => __('Required field')],
        ];
    }

}
