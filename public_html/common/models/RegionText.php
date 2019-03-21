<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "regions_text".
 *
 * @property integer $id
 * @property integer $regions_id
 * @property integer $languages_id
 * @property string $name
 * @property string $application_url
 * @property string $name_rp
 * @property string $name_pp
 *
 * @property Region $regions
 * @property Language $languages
 */
class RegionText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'regions_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['regions_id', 'name'], 'required'],
            [['regions_id', 'languages_id'], 'integer'],
            [['name', 'name_rp', 'name_pp', 'application_url'], 'string', 'max' => 255],
            [['regions_id'], 'exist', 'skipOnError' => true, 'targetClass' => Region::className(), 'targetAttribute' => ['regions_id' => 'id']],
            [['languages_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['languages_id' => 'id']],
            [['languages_id'],'default', 'value' => Language::getDefault()->id],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'regions_id' => 'Regions ID',
            'languages_id' => 'Languages ID',
            'name' => 'Name',
            'application_url' => 'Url подачи',
            'name_rp' => 'Name Rp',
            'name_pp' => 'Name Pp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regions_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'languages_id']);
    }
}
