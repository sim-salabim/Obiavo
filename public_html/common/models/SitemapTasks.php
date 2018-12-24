<?php
namespace common\models;

/**
 * Class SitemapTasks
 * @package common\models
 *
 * @property string id
 * @property integer $countries_id
 * @property string $status
 * @property string $created_at
 *
 * @property Country $country
 */
class SitemapTasks extends \yii\db\ActiveRecord
{

    const PROCESSING_STATUS = 'PROCESSING';
    const PENDING_STATUS = 'PENDING';
    const FAILED_STATUS = 'FAILED';
    const FINISHED_STATUS = 'FINISHED';

    static function tableName()
    {
        return 'sitemap_tasks';
    }

    /**
     * @inheritdoc
     */
    function rules()
    {
        return [
            [['countries_id'], 'integer'],
            [['status'], 'string', 'max' => 255],
            [['status'], 'validateStatus', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['countries_id'], 'required'],
        ];
    }

    public function validateStatus($attribute,$params, $validator){
        $statArr = [
            self::PROCESSING_STATUS,
            self::PENDING_STATUS,
            self::FAILED_STATUS,
            self::FINISHED_STATUS
        ];
        if(!in_array($this->status, $statArr)){
            $this->addError('status','Недопустимое значение');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'countries_id']);
    }
}