<?php

namespace common\models;

/**
 * This is the model class for table "counter_city_category".
 *
 * @property integer $id
 * @property integer $cities_id
 * @property integer $categories_id
 *
 * @property Category[] $category
 * @property City $city
 */
class CounterCityCategory extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counter_city_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categories_id', 'cities_id'], 'required'],
            [['categories_id', 'cities_id'], 'integer'],
            [['cities_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['cities_id' => 'id']],
            [['categories_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['categories_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'categories_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cities_id']);
    }
}