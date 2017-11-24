<?php

namespace common\models;

use common\models\libraries\AdsSearch;
use frontend\helpers\ArrayHelper;
use frontend\helpers\LocationHelper;
use Yii;
use frontend\helpers\TransliterationHelper;

/**
 * This is the model class for table "ads".
 *
 * @property integer $id
 * @property integer $ads_id
 * @property integer $users_id
 * @property integer $created_at
 *
 * @property Ads $ad
 * @property User $user
 *
 */
class AdsView extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ads_views';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ads_id'], 'required'],
            [['ads_id', 'users_id'], 'integer'],
            [['ads_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ads::className(), 'targetAttribute' => ['ads_id' => 'id']],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['users_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ads_id' => 'Ad',
            'users_id' => 'User',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'users_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAd()
    {
        return $this->hasOne(Ads::className(), ['id' => 'ads_id']);
    }
    /**
     *   Добавляет запись о просмотре страницы обьявления
     *
     * @param $ad_id
     * @param null $user_id
     */
    public static function eraseView($ad_id, $user_id = null){
        $model = new self();
        $model->users_id = $user_id;
        $model->ads_id = $ad_id;
        $model->save();
    }
}