<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "categories_generated".
 *
 * @property integer $id
 * @property integer $categories_id
 * @property integer $countries_id
 * @property string $url
 * @property string $techname
 * @property string $seo_name
 * @property string $seo_title
 * @property string $seo_desc
 * @property string $seo_keywords
 * @property integer $order
 *
 * @property Countries $countries
 * @property Categories $categories
 */
class CategoryGenerate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories_generated';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categories_id', 'countries_id', 'url', 'techname', 'seo_name', 'seo_keywords', 'order'], 'required'],
            [['categories_id', 'countries_id', 'order'], 'integer'],
            [['url', 'techname', 'seo_name', 'seo_title', 'seo_desc', 'seo_keywords'], 'string', 'max' => 255],
            [['countries_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['countries_id' => 'id']],
            [['categories_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['categories_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categories_id' => 'Categories ID',
            'countries_id' => 'Countries ID',
            'url' => 'Url',
            'techname' => 'Techname',
            'seo_name' => 'Seo Name',
            'seo_title' => 'Seo Title',
            'seo_desc' => 'Seo Desc',
            'seo_keywords' => 'Seo Keywords',
            'order' => 'Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasOne(Countries::className(), ['id' => 'countries_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasOne(Categories::className(), ['id' => 'categories_id']);
    }
}
