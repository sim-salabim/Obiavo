<?php

namespace common\models;

use Yii;
use common\models\LanguageText;

/**
 * This is the model class for table "languages".
 *
 * @property integer $id
 * @property string $name
 * @property string $desc
 *
 */
class FilesExtsTypes extends \yii\db\ActiveRecord
{
    public static $_allLanguages;

    public static function tableName()
    {
        return 'files_exts_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => __('Name'),
            'desc' => __('Description'),
        ];
    }

}
