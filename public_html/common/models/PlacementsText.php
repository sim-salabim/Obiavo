<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "placements_text".
 *
 * @property integer $id
 * @property integer $languages_id
 * @property integer $placements_id
 * @property string $name
 * @property string $apply_url
 *
 * @property Placements $placements
 * @property Languages $languages
 */
class PlacementsText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'placements_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['placements_id', 'name', 'url'], 'required'],
            [['placements_id'], 'integer'],
            [['name', 'url', 'apply_url'], 'string', 'max' => 255],
            [['placements_id'], 'exist', 'skipOnError' => true, 'targetClass' => Placement::className(), 'targetAttribute' => ['placements_id' => 'id']],
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
            'languages_id' => 'Languages ID',
            'placements_id' => 'Placements ID',
            'name' => 'Название',
            'url' => 'SEO Url',
            'apply_url' => 'Application Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacements()
    {
        return $this->hasOne(Placements::className(), ['id' => 'placements_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasOne(Languages::className(), ['id' => 'languages_id']);
    }
}
