<?php
/**
 * Класс реализует вывод форматированного перевода для текущей записи
 *
 * @example $object->_text->attribute
 *
 */

namespace frontend\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;
use yii\base\Exception;

/**
 * Поведение для получения значения аттрибута в зависимсоит от языка
 * ```
 *
 * @property ActiveRecord $owner
 */
class Multilanguage extends Behavior
{

    /**
     * Название связи используемой для перевода
     */
    public $relationName = '';

    public $relationClassName = '';

    /**
     * Поле используемое для перевода
     */
    public $field = 'name';

    /**
     * Дефолтный текст при отсутствии перевода
     */
    public $text = 'Нет перевода';

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_INIT => 'Init',
            ActiveRecord::EVENT_AFTER_INSERT => 'updateRelationEvent'
        ];
    }

    public $_text = '';

    /**
     * Проверяем есть ли текстовые данные связанной модели у объекста по текущему языку
     * Если нет, то добавляем
     */
    public function afterFind(){
        $multiText = $this->owner->{$this->relationName};

        if (! $multiText) {
            $this->setMultiRelated();
        }

        $this->_text = $this->owner->{$this->relationName};
    }
    
    public function init(){
        $this->_text = new $this->relationClassName;
    }
    
    public function updateRelationEvent(){
        
        $this->_text = $this->owner->{$this->relationName};
    }

    /**
     * Добавить текст с
     */
    private function setMultiRelated(){

        $getter = "get{$this->relationName}";

        $multiText = $this->owner->$getter()->one();

        if (! $multiText) {
            $multiText = Yii::createObject($this->relationClassName);
        }

        $this->owner->populateRelation($this->relationName, $multiText);
    }


}