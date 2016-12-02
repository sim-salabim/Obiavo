<?php
/**
 * Класс реализует вывод форматированного перевода для текущей записи
 *
 * @example $object->_text
 *
 */

namespace frontend\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;
use yii\base\Exception;

/**
 * Поведение для сохранения связанных моделей
 * ```
 *
 * @property ActiveRecord $owner
 */
class Multilanguage extends Behavior
{

    /**
     * Название связи используемой для перевода
     */
    public $multirelation = '';

    /**
     * Поле используемое для перевода
     */
    public $field = 'name';

    /**
     * Дефолтный текст при отсутствии перевода
     */
    public $text = 'Нет перевода';

//    private $_text = '';


    public function get_text() {
        
        return yii\helpers\ArrayHelper::getValue($this->owner->{$this->multirelation}, $this->field ,$this->text);
    }
}