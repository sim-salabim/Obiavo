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


    public function multiOff(){
        $this->detachBehavior('multiText');
    }

    public function init(){
        $this->_text = new $this->relationClassName;
    }

    public function get_texts(){
       return $this->owner->{$this->relationName."s"};
    }

    /**
     * Проверяем есть ли текстовые данные связанной модели у объекста по текущему языку
     * Если нет, то добавляем
     *
     * ПОЯСНЕНИЕ:
     * в _text всегда находится перевод текущей системы, незивисимо от запрашивемого текста
     * а в реляции находится необходимый в данный момент перевод, либо если он отстствует = NULL
     * поэтому при отсутствии запрашиваемого перевода, пытаемся заполнить только _text
     */
    public function afterFind(){
        $multiText = $this->owner->{$this->relationName};

        if (! $multiText) {
            $multiText = $this->getMultiRelated();
        }

        $this->_text = $multiText;

//        $this->_text = $this->owner->{$this->relationName};
    }

    public function updateRelationEvent(){

        $this->_text = $this->owner->{$this->relationName};
    }

    /**
     * Добавить текст с
     */
    private function getMultiRelated(){

        $getter = "get{$this->relationName}";

        $multiText = $this->owner->$getter()->one();

        if (! $multiText) {
            $multiText = Yii::createObject($this->relationClassName);
        }

        return $multiText;

//        $this->owner->populateRelation($this->relationName, $multiText);
    }


}