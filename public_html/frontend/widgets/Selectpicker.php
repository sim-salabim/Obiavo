<?php
namespace frontend\widgets;

use yii\base\Widget;

/**
 * Виджет для селектора select-bootstrap
 *
 * @var array $values       - Массив значений
 * @var array $name         - Аттрибут name
 */
class Selectpicker extends Widget {

    public $values = [];
    public $name = '';

    public function init(){
        parent::init();

    }

    public function run(){
        return $this->html();
    }

    /**
     * @return text <select><option></option></select>
     */
    private function html(){
        $html = "<select name=\"{$this->name}\" class=\"selectpicker\">";
        foreach ($this->values as $key => $value){
            $html .= "<option>$value</option>";
        }
        $html .= "</select>";

        return $html;
    }
}