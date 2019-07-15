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
    const STATUS_IN_PROGRESS = 'IN_PROGRESS';
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
            [['created_at', 'posted_at', 'supposed_at'], 'string']
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

    /**
     *  Создает задачу для автопостинга для всех соцсетей для которых он включен
     *
     * @param Ads $ad, экземпляр класса объявлений
     */
    static public function createTasks(Ads $ad){
        $networks = SocialNetworks::getNetworksForAutoposting();
        foreach($networks as $network){
            $group = $network->getGroupForAutoposting($ad);
            if($group and $group->countries_id == $ad->city->region->country->id) {
                $task = new self();
                $task->ads_id = $ad->id;
                $task->social_networks_groups_id = $group->id;
                $task->save();
            }
        }
    }
}
