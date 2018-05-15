<?php

namespace common\models;

/**
 * This is the model class for table "test-tasks".
 *
 * @property integer $id
 * @property integer $ads_id
 * @property integer $social_networks_groups_id
 *
 * @property Ads $ad
 * @property SocialNetworksGroups $socialNetworksGroup
 *
 */
class TestTasks extends \yii\db\ActiveRecord
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
        return 'test_tasks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ads_id', 'social_networks_groups_id'], 'required'],
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

}
