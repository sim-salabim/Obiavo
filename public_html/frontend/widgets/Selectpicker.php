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
        
        $html = "<select name=\"{$this->name}\" $options class=\"selectpicker\">";
        foreach ($this->values as $key => $value){
            $html .= "<option>$value</option>";
        }
        $html .= "</select>";

        return $html;
    } 
    
    protected function getOptions(){
        $html = '';
        
        foreach($this->options as $name => $opt){            
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
}