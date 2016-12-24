<?php

namespace common\models;

use Yii;
use common\models\scopes\CountryQuery;

/**
 * This is the model class for table "countries".
 *
 * @property integer $id
 * @property integer $languages_id
 * @property string $domain
 * @property integer $active
 * @property string $meta_google
 * @property string $meta_yandex
 * @property string $longitude
 * @property string $latitude
 *
 * @property CategoryGenerated[] $categoryGenerated
 * @property Languages $languages
 * @property CountriesText[] $countriesTexts
 * @property Regions[] $regions
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain'], 'required'],
            [['languages_id', 'active'], 'integer'],
            [['domain', 'meta_google', 'meta_yandex'], 'string', 'max' => 255],
            [['longitude', 'latitude'], 'string', 'max' => 100],
            [['languages_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['languages_id' => 'id']],
            [['languages_id'],'default', 'value' => Yii::$app->user->getDefaultLanguage()->id],
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
            'domain' => 'Domain',
            'active' => 'Active',
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
                    'relations' => ['countryText']
                ],
                [
                    'class' => \frontend\behaviors\Multilanguage::className(),
                    'relationName' => 'countryText',
                    'relationClassName' => CityText::className(),
                ],
            ];
    }

    public static function find(){
        return new CountryQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryGenerateds()
    {
        return $this->hasMany(CategoryGenerated::className(), ['countries_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasOne(Language::className(), ['id' => 'languages_id']);
    }

    public function getCountryText()
    {
        return $this->hasOne(CountryText::className(), ['countries_id' => 'id']);
    }

    public function getCountryTexts()
    {
        return $this->hasMany(CountryText::className(), ['countries_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this->hasMany(Region::className(), ['countries_id' => 'id']);
    }
}
