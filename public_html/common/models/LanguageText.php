<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "languages_text".
 *
 * @property integer $id
 * @property integer $languages_id
 * @property string $name
 *
 * @property Languages $languages
 */
class LanguageText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'languages_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['languages_id', 'name'], 'required'],
            [['languages_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['languages_id'], 'exist', 'skipOnError' => true, 'targetClass' => Languages::className(), 'targetAttribute' => ['languages_id' => 'id']],
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
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasOne(Languages::className(), ['id' => 'languages_id']);
    }
}
