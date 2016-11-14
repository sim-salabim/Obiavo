<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "countries_text".
 *
 * @property integer $id
 * @property integer $countries_id
 * @property string $name
 * @property string $name_rp
 * @property string $name_pp
 *
 * @property Countries $countries
 */
class CountryText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countries_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['countries_id', 'name'], 'required'],
            [['countries_id'], 'integer'],
            [['name', 'name_rp', 'name_pp'], 'string', 'max' => 255],
            [['countries_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['countries_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'countries_id' => 'Countries ID',
            'name' => 'Name',
            'name_rp' => 'Name Rp',
            'name_pp' => 'Name Pp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasOne(Country::className(), ['id' => 'countries_id']);
    }
}
