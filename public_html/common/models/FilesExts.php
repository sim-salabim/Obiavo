<?php

namespace common\models;

use Yii;
use common\models\LanguageText;

/**
 * This is the model class for table "languages".
 *
 * @property integer $id
 * @property string $ext
 * @property string $mime
 * @property int $files_exts_types_id
 *
 * @property FilesExtsTypes $ext_type
 */
class FilesExts extends \yii\db\ActiveRecord
{
    public static $_allLanguages;

    public static function tableName()
    {
        return 'files_exts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ext', 'files_exts_types_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ext' => __('Extension'),
            'desc' => __('Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtType()
    {
        return $this->hasOne(FilesExtsTypes::className(), ['id' => 'files_exts_types_id']);
    }
}
