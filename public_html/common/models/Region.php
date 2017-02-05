<?php

namespace common\models;

use Yii;
use common\models\scopes\RegionQuery;

/**
 * This is the model class for table "regions".
 *
 * @property integer $id
 * @property integer $countries_id
 * @property integer $active
 * @property string $domain
 * @property string $meta_google
 * @property string $meta_yandex
 * @property string $longitude
 * @property string $latitude
 *
 * @property City[] $cities
 * @property Country $countries
 * @property RegionText[] $regionsTexts
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'regions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['countries_id', 'domain'], 'required'],
            [['countries_id', 'active'], 'integer'],
            [['domain', 'meta_google', 'meta_yandex'], 'string', 'max' => 255],
            [['longitude', 'latitude'], 'string', 'max' => 100],
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
            'active' => 'Active',
            'domain' => 'Domain',
            'meta_google' => 'Meta Google',
            'meta_yandex' => 'Meta Yandex',
            'longitude' => 'Долгота',
            'latitude' => 'Широта',
        ];
    }

    public function behaviors()
    {
            return [
                [
                    'class' => \backend\behaviors\SaveRelation::className(),
                    'relations' => ['regionText']
                ],
                [
                    'class' => \frontend\behaviors\Multilanguage::className(),
                    'relationName' => 'regionText',
                    'relationClassName' => RegionText::className(),
                ],
            ];
    }

    public static function find(){
        return new RegionQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['regions_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'countries_id']);
    }

    public function getRegionText()
    {
        return $this->hasOne(RegionText::className(), ['regions_id' => 'id']);
    }

    public function multiText(){
        return $this->hasOne(RegionText::className(), ['regions_id' => 'id']);
//                    ->andWhere(['regions_text.languages_id' => Yii::$app->user->getLanguage()->id]);
    }

    public function getRegionTexts()
    {
        return $this->hasMany(RegionText::className(), ['regions_id' => 'id']);
    }
}
