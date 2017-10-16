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
    const THUMBNAIL = '_thumbnail';

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
        return false;
    }

    /**
     * @param $id, id файла
     * @return string, абсолютный путь
     */
    public static function getFileById($id){
        $file = self::findOne(['id' => $id]);
        if($file->id){
            if(file_exists(Yii::$app->params['uploadPath']."/".$file->hash)){
                return "/files/".$file->hash.".".$file->ext->ext;
            }
        }
        return false;
    }

    /**
     * @param $id
     * @param bool $thumbnail
     * @return string
     */
    public static function getImageById($id, $thumbnail = true){
        $file = Files::findOne(['id' => $id]);
        $thumbnail_str = ($thumbnail) ? Files::THUMBNAIL : '';
        if($file->id){
            if(file_exists(Yii::$app->params['uploadPath']."/".$file->hash)){
                return "/files/".$file->hash.$thumbnail_str;
            }
        }
        return "/files/placeholder".$thumbnail_str.".png";
    }
    /**
     * @param $id
     * @param bool $thumbnail
     * @return string
     */
    public function getImage($thumbnail = true){
        $thumbnail_str = ($thumbnail) ? Files::THUMBNAIL : '';
        if($this->id){
            if(file_exists(Yii::$app->params['uploadPath']."/".$this->hash)){
                return "/files/".$this->hash.$thumbnail_str;
            }
        }
        return "/files/placeholder".$thumbnail_str.".png";
    }

    /**
     *  Удаляет файл из директории и из базы
     */
    public function deleteFile(){
        if(file_exists(Yii::$app->params['uploadPath']."/".$this->hash)){
            unlink(Yii::$app->params['uploadPath']."/".$this->hash);
            unlink(Yii::$app->params['uploadPath']."/".$this->hash.Files::THUMBNAIL);
        }
        $this->delete();
    }

    /**
     * @param $files_arr, $_POST['files']
     * @param $model, сохраненный обьект, имеючий связь через промежуточную таблицу
     */
    public static function linkFilesToModel($files_arr, $model){
        if(isset($files_arr) AND !empty(isset($files_arr))){
            $files = Files::find()->where(['in', 'id', $files_arr])->all();
            if(count($files)){
                foreach ($files as $file){
                    $model->link('files', $file);
                }
            }
        }
    }
}
