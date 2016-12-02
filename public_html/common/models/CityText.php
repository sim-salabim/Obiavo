<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cities_text".
 *
 * @property integer $id
 * @property integer $cities_id
 * @property integer $languages_id
 * @property string $name
 * @property string $name_rp
 * @property string $name_pp
 *
 * @property City $cities
 * @property Language $languages
 */
class CityText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cities_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cities_id', 'name'], 'required'],
            [['cities_id', 'languages_id'], 'integer'],
            [['name', 'name_rp', 'name_pp'], 'string', 'max' => 255],
            [['cities_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['cities_id' => 'id']],
            [['languages_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['languages_id' => 'id']],
            [['languages_id'],'default', 'value' => Language::getLanguageDeafault()->id],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cities_id' => 'Cities ID',
            'languages_id' => 'Languages ID',
            'name' => 'Name',
            'name_rp' => 'Name Rp',
            'name_pp' => 'Name Pp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cities_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'languages_id']);
    }
}
