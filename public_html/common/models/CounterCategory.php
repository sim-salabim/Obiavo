<?php

namespace common\models;

use Yii;
use common\models\scopes\CityQuery;

/**
 * This is the model class for table "counter_category".
 *
 * @property integer $id
 * @property integer $countries_id
 * @property integer $categories_id
 *
 * @property Category[] $category
 * @property Country $country
 */
class CounterCategory extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counter_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categories_id', 'countries_id'], 'required'],
            [['categories_id', 'countries_id'], 'integer'],
            [['countries_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['countries_id' => 'id']],
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
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'countries_id']);
    }
}