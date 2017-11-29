<?php

namespace common\models;

use common\models\scopes\CityOrderQuery;
use Yii;
use common\models\scopes\CityQuery;

/**
 * This is the model class for table "cities".
 *
 * @property integer $id
 * @property integer $cities_id
 * @property integer $order
 *
 * @property CityText[] $cityTexts
 * @property City $city
 */
class CityOrder extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cities_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cities_id'], 'required'],
            [['order'], 'integer'],
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
            'order' => 'Order',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => \frontend\behaviors\Multilanguage::className(),
                'relationName' => 'cityText',
                'relationClassName' => CityText::className(),
            ],
        ];
    }

    public static function find(){
        return new CityOrderQuery(get_called_class());
    }

    public function getCityText()
    {
        return $this->hasOne(CityText::className(), ['cities_id' => 'cities_id']);
//                    ->andWhere(['cities_text.languages_id' => Yii::$app->user->getLanguage()->id]);
    }

    public function getCityTexts()
    {
        return $this->hasMany(CityText::className(), ['cities_id' => 'cities_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cities_id']);
           //->with('cityText');
    }
}
