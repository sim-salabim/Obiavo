<?php

namespace common\models;

use Yii;
use common\models\LanguageText;

/**
 * This is the model class for table "languages".
 *
 * @property integer $id
 * @property string $name
 * @property string $hash
 * @property int $files_exts_id
 * @property int $users_id
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
            [['name', 'files_exts_id', 'users_id', 'hash'], 'required'],
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
            'hash' => 'Hash',
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

    /**
     * @return string
     */
    public function getFilePath(){
        $file = self::findOne(['id' => $this->id]);
        if($file->id){
            if(file_exists(Yii::$app->params['uploadPath']."/".$file->hash)){
                return Yii::$app->params['uploadPath']."/".$file->hash.".".$file->ext->ext;
            }
        }
        return Yii::$app->params['uploadPath']."/placeholder.png";
    }

    /**
     * @param $id, id файла
     * @return string, абсолютный путь
     */
    public static function getFileById($id){
        $file = self::findOne(['id' => $id]);
        if($file->id){
            if(file_exists(Yii::$app->params['uploadPath']."/".$file->hash)){
                return Yii::$app->params['uploadPath']."/".$file->hash.".".$file->ext->ext;
            }
        }
        return Yii::$app->params['uploadPath']."/placeholder.png";
    }

    /**
     *  Удаляет файл из директории и из базы
     */
    public function deleteFile(){
        if(file_exists(Yii::$app->params['uploadPath']."/".$this->hash)){
            unlink(Yii::$app->params['uploadPath']."/".$this->hash);
        }
        $this->delete();
    }
}
