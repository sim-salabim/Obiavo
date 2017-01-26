<?php
namespace backend\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\ArrayHelper as AH;
use yii\base\Widget;

class Form extends Widget {

    public $rows = [];

    const INPUT_TEXT = 'textInput';

    CONST INPUT_TEXT_ACTIVE = 'textActiveInput';

    const INPUT_CHECKBOX_INACTIVE = 'inputCheckboxInactive';

    public function init()
    {
        parent::init();
    }

    public function run(){
        $rows = $this->rows;

        foreach ($rows as $key => $item){
            $rows[$key]['panel-form-content'] = [];
            $panelForm = '';

            foreach ($item['attributes'] as $attribute) {

                $inputType = $attribute['type'];

                $panelForm .= $this->$inputType($attribute);
            }

            $rows[$key]['panel-form-content'] = $panelForm;
        }

        return $this->render('base',compact('rows'));
    }

    protected function textInput($attribute){

        return $this->render('textinput', compact('attribute'));
    }

    protected function inputCheckboxInactive($attribute){

        return $this->render('input-checkbox-inactive', compact('attribute'));
    }
}