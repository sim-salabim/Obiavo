<?php
namespace common\models;

/**
 * Class SitemapIndex
 * @package common\models
 *
 * @property string id
 * @property integer $countries_id
 * @property integer $tasks_id
 * @property string $link
 * @property string $created_at
 *
 * @property Country $country
 * @property SitemapTasks $task
 */
class SitemapIndex extends \yii\db\ActiveRecord
{
    static function tableName()
    {
        return 'sitemap_index';
    }


    /**
     * @inheritdoc
     */
    function rules()
    {
        return [
            [['created_at', 'link'], 'string', 'max' => 255],
            [['countries_id', 'tasks_id'], 'integer'],
            [['countries_id', 'tasks_id', 'link'], 'required'],
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasMany(SitemapTasks::className(), ['id' => 'tasks_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'countries_id']);
    }
}