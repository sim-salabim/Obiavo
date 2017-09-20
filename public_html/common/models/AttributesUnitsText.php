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
class AttributesUnitsText extends \yii\db\ActiveRecord
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
            [['attributes_units_id', 'languages_id', 'name'], 'required'],
            [['attributes_units_id', 'languages_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['attributes_units_id'], 'exist', 'skipOnError' => true, 'targetClass' => AttributesUnits::className(), 'targetAttribute' => ['attributes_units_id' => 'id']],
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
            'attributes_units_id' => \Yii::t('app', 'Unit'),
            'languages_id' => 'Languages ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'languages_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeUnit()
    {
        return $this->hasOne(AttributesUnits::className(), ['id' => 'attributes_units_id']);
    }
}
