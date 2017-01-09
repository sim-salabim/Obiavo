<?php
namespace backend\helpers;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\ArrayHelper as AH;

/**
 * Возвращает html поле для стндартной формы
 */
class FormHtmlTag {
       
    private static $attribute = '';
    private static $format = 'text';
    private static $label = '';
    private static $options = [];
    private static $tagParams = [];   
    
    private static $model = null;

    /**
     *
     * @param array $attributes ['attributes'=>'attribute:format:label','model'=> $object, options => []]
     */
    public static function row($column){

        self::normalize($column);

        return self::{self::$format}();
    }

    protected function text(){

        $input = Html::activeTextInput(self::$model, self::$attribute,['class' => 'form-control']);

        $htmlTag = Html::beginTag('div', ['class' => 'form-group row']);

            $htmlTag .= Html::tag('label',self::$label,['class' => 'col-xs-2 col-form-label']);
            $htmlTag .= Html::tag('div',$input,['class' => 'col-xs-10']);

        $htmlTag .= Html::endTag('div');

        return $htmlTag;
    }

    protected function checkbox(){

        $input = Html::activeCheckbox(self::$model, self::$attribute,['label' => false, 'class' => 'input-group-addon']);

        $htmlTag = Html::beginTag('div', ['class' => 'form-group row']);

            $htmlTag .= Html::tag('label',self::$label,['class' => 'col-xs-2 col-form-label']);
            $htmlTag .= Html::beginTag('div',['class' => 'col-xs-10']);
                $htmlTag .= Html::beginTag('div',['class' => 'input-group']);
                    $htmlTag .= Html::tag('span',$input,['class' => 'input-group-addon']);
                    $htmlTag .= Html::tag('span','Этот пункт используется на сайте',['class' => 'form-control']);
                $htmlTag .= Html::endTag('div');
            $htmlTag .= Html::endTag('div');

        $htmlTag .= Html::endTag('div');

        return $htmlTag;
    }

    protected function buttonInput(){

        $btnT =  AH::getValue(self::$tagParams, ['btn-text'], '');

        $btnOpt =  AH::getValue(self::$options, ['btn'], ['class' => 'btn btn-info']);
        $inputOpt =  AH::getValue(self::$options, ['input'], ['class' => 'form-control']);

        $btn = Html::tag('button',$btnT, AH::getValue(self::$options, ['options','btn'], $btnOpt));

        $htmlTag = Html::beginTag('div', ['class' => 'form-group row']);

            $htmlTag .= Html::tag('label', self::$label, ['class' => 'col-xs-2 col-form-label']);
            $htmlTag .= Html::beginTag('div',['class' => 'col-xs-10']);
                $htmlTag .= Html::beginTag('div',['class' => 'input-group']);
                    $htmlTag .= Html::tag('div',$btn,['class' => 'input-group-btn']);
                    $htmlTag .= Html::tag('input', '', $inputOpt);
                $htmlTag .= Html::endTag('div');

            $htmlTag .= Html::endTag('div');

        $htmlTag .= Html::endTag('div');

        return $htmlTag;
    }
    
    protected static function selectMultiple(){
        $model = self::$model;
        $elements = AH::getValue(self::$options,'multiple.elements',[]);
        $selected = AH::getValue(self::$options,'multiple.selected',[]);
        
        $selectpicker = \frontend\widgets\Selectpicker::widget([
                                    'values' => $elements,
                                    'selected' => $selected,
                                    'name' => self::$attribute,
                                    'options' => [
                                        'multiple' => true
                                    ]
                        ]);

        $htmlTag = Html::beginTag('div', ['class' => 'form-group row']);
        
            $htmlTag .= Html::tag('label',self::$label,['class' => 'col-xs-2 col-form-label']);
            
            $htmlTag .= Html::tag('div',$selectpicker,['class' => 'col-xs-10']);
            
        $htmlTag .= Html::endTag('div');
        
        return $htmlTag;
    }

    protected function normalize($column){
        $attributes = $column['attributes'];

        if (!is_string($attributes)) {
            throw new InvalidConfigException('The attribute configuration must be is string.');
        }

        if (!preg_match('/^([\w\.]+)(:([a-zA-z-]*))?(:(.*))?$/', $attributes, $matches)) {
            throw new InvalidConfigException('The attribute must be specified in the type of "attribute", "attribute:format:label"');
        }
        
        self::$attribute =  $matches[1];
        self::$format = AH::getValue($matches, 3, 'text');
        self::$label = AH::getValue($matches, 5, null);
        self::$options = AH::getValue($column, 'options', []);
        self::$tagParams = AH::getValue($column, 'params', []);        

        self::$model = $column['model'];
        
        self::$format = str_replace('-', '', ucwords(self::$format, '-'));
    }
}