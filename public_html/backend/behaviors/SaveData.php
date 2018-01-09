<?php
/**
 * Класс реализует сохранение данных модели из полученных из $_POST данных
 *
 *
 */

namespace backend\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Поведение для сохранения данных для моделей
 *
 *
 * @property ActiveRecord $owner
 */
class SaveData extends Behavior
{
    /**
     * @param array $data ($array[key=>value, key=>value ....])
     * @return bool
     */
    function saveData($data = []){
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $this->owner->{$key} = $value;
            }
            if(!$this->owner->save()){
                return false;
            }
        }
        return true;
    }
}