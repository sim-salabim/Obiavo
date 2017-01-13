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

    /**
     *  --php
     *
     *  [ 'first' => ['value] ]
     *
     *  OR
     *
     *  [ 'first' => ['key' => 'value'] ]
     */
    public $first = [];


    public $values = [];
    public $name = '';
    /**
     * номера выбранных элементов
     */
    public $selected = [];

    /**
     *    [
     *      'multiple' => false,
     *      'data-live-search' => "true"
     *    ]
     */
    public $options = [];

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
        $options = $this->getOptions();

        $html = "<select name=\"{$this->name}\" $options>";

        $html .= $this->first();

        foreach ($this->values as $key => $value){

            $selected = $this->isSelected($key) ? 'selected' : '';

            $html .= "<option value='$key' $selected>$value</option>";
        }
        $html .= "</select>";

        return $html;
    }

    protected function getOptions(){
        $html = '';
        $opt = $this->options;

        if (empty($opt['class'])) {
                $opt['class'] = 'selectpicker';
        } else {
            $opt['class'] = $opt['class'].' selectpicker';
        }

        foreach($opt as $name => $opt){
            if (!$opt) continue;

            if ($opt === true) {
                $html .= "$name";

            } elseif ($opt) {
                $html .= "$name=\"$opt\"";
            }

            $html = "$html ";
        }

        return trim($html);
    }

    protected function isSelected($key){

        if (in_array($key,$this->selected)){
            return true;
        }

        return false;
    }

    protected function first(){
        $html = '';

        if (!empty($this->first)){
            $key = key($this->first);
            $html .= "<option value=\"$key\">{$this->first[$key]}</option>";

        }

        return $html;
    }
}