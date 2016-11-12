<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "languages".
 *
 * @property integer $id
 * @property string $code
 * @property integer $active
 * @property integer $is_default
 *
 * @property CategoriesAttributesText[] $categoriesAttributesTexts
 * @property CitiesText[] $citiesTexts
 * @property Countries[] $countries
 * @property LanguagesText[] $languagesTexts
 * @property RegionsText[] $regionsTexts
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'languages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['active', 'is_default'], 'integer'],
            [['code'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'active' => 'Active',
            'is_default' => 'Is Default',
        ];
    }

    public function behaviors()
    {
            return [
                [
                    'class' => \backend\behaviors\SaveRelation::className(),
                    'relations' => ['text']
                ]
            ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesAttributesTexts()
    {
        return $this->hasMany(CategoriesAttributesText::className(), ['languages_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCityTexts()
    {
        return $this->hasMany(CityText::className(), ['languages_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(Country::className(), ['languages_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getText()
    {
        return $this->hasMany(LanguageText::className(), ['languages_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegionsTexts()
    {
        return $this->hasMany(RegionsText::className(), ['languages_id' => 'id']);
    }

    public function saveUpdateData(){

    }

    public function saveNewData(){

    }
}
