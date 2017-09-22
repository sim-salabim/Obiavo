<?php

namespace common\models;

use Yii;
use common\models\LanguageText;

/**
 * This is the model class for table "languages".
 *
 * @property integer $id
 * @property string $mime
 * @property int $files_exts_id
 * @property int $users_id
 * @property int $files_exts_types_id
 *
 * @property User $user
 * @property FilesExts $ext
 * @property FilesExtsTypes $ext_type
 */
class Files extends \yii\db\ActiveRecord
{
    public static $_allLanguages;

    public static function tableName()
    {
        return 'files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'files_exts_id', 'users_id', 'files_exts_types_id'], 'required'],
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'users_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExt()
    {
        return $this->hasOne(FilesExts::className(), ['id' => 'files_exts_id']);
    }

}
