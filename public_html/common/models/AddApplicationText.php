<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "add_application_text".
 *
 * @property integer $id
 * @property integer $add_application_id
 * @property integer $languages_id
 * @property string $url
 * @property string $seo_title
 * @property string $seo_h1
 * @property string $seo_h2
 * @property string $seo_desc
 * @property string $seo_keywords
 * @property string $seo_text
 *
 * @property AddApplication $add_application
 * @property Language $languages
 */
class AddApplicationText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'add_application_text';
    }
    const SCENARIO_DEFAULT = 'default';

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'add_application_id',
                'languages_id',
                'seo_title',
                'url',
                'seo_text',
                'seo_h1',
                'seo_h2',
                'seo_desc',
                'seo_keywords',
                'category_default',
                'placements_default'
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_application_id', 'seo_title', 'seo_text', 'url'], 'required'],
            [['add_application_id', 'languages_id'], 'integer'],
            [['seo_title'], 'string', 'max' => 255],
            [['placements_default', 'category_default'], 'integer', 'max' => 1, 'min' => 0],
            [['add_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => AddApplication::className(), 'targetAttribute' => ['add_application_id' => 'id']],
            [['languages_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['languages_id' => 'id']],
            [['languages_id'],'default', 'value' => Language::getDefault()->id],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'add_application_id' => 'Add application ID',
            'languages_id' => 'Languages ID',
            'url' => 'URL',
            'seo_title' => 'Title',
            'seo_text' => 'Text',
            'seo_h1' => 'H1',
            'seo_h2' => 'H2',
            'seo_desc' => 'Description',
            'seo_keywords' => 'Keywords',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddApplication()
    {
        return $this->hasOne(AddApplication::className(), ['id' => 'add_application_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'languages_id']);
    }
}
