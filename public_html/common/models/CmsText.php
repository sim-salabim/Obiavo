<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cities_text".
 *
 * @property integer $id
 * @property integer $cms_id
 * @property integer $languages_id
 * @property string $title
 * @property string $text
 *
 * @property Cms $cms
 * @property Language $languages
 */
class CmsText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_text';
    }
    const SCENARIO_DEFAULT = 'default';

    public function scenarios()
     {
         return [
             self::SCENARIO_DEFAULT => [
                 'cms_id',
                 'languages_id',
                 'name',
                 'url',
                 'text'
             ],
         ];
     }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cms_id', 'name'], 'required'],
            [['cms_id', 'languages_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['cms_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cms::className(), 'targetAttribute' => ['cms_id' => 'id']],
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
            'cms_id' => 'CMS ID',
            'languages_id' => 'Languages ID',
            'name' => 'Title',
            'text' => 'Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCms()
    {
        return $this->hasOne(City::className(), ['id' => 'cms_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'languages_id']);
    }
}
