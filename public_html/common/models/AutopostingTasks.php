<?php

namespace common\models;

/**
 * This is the model class for table "ads".
 *
 * @property integer $id
 * @property integer $ads_id
 * @property integer $social_networks_groups_id
 * @property string $status
 * @property boolean $priority
 * @property \DateTime $create_at
 * @property \DateTime $posted_at
 * @property \DateTime $supposed_at
 *
 * @property Ads $ad
 * @property SocialNetworksGroups $socialNetworksGroup
 *
 */
class AutopostingTasks extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 'PENDING';
    const STATUS_FAILED = 'FAILED';
    const STATUS_POSTED = 'POSTED';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'autoposting_tasks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ads_id', 'social_networks_groups_id'], 'required'],
            [['status'], 'string'],
            [['text', ], 'string', 'max' => 1000],
            [['create_at', 'posted_at', 'supposed_at']]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAd()
    {
        return $this->hasOne(Ads::className(), ['id' => 'ads_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialNetworksGroup()
    {
        return $this->hasOne(SocialNetworksGroups::className(), ['id' => 'social_networks_groups_id']);
    }

    /** Возвращает строку с human date
     * @return string
     */
    public function getHumanDate(){
        $ad_day_number = date('z', $this->created_at);
        $today_number = date('z', time());
        $daystr = '';
        if($ad_day_number == $today_number){
            $daystr .= __('Today');
        }else if($today_number == (1 + $ad_day_number)){
            $daystr .= __('Yesterday');
        }else{
            $daystr .= date('d:m:y', $this->created_at);
        }
        $daystr .= ' '.date('H:i', $this->created_at);
        return $daystr;
    }
}
