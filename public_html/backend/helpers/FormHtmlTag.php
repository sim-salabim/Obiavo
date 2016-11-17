<?php
namespace backend\helpers;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Возвращает html поле для стндартной формы
 */
class FormHtmlTag {

    private static $attribute = '';
    private static $format = 'text';
    private static $label = '';

    private static $model = null;

    /**
     *
     * @param text $text 'attribute:format:label'
     * @param object $model
     */
    public static function row($attribute, $model){

        if (!is_string($attribute)) {
            throw new InvalidConfigException('The attribute configuration must be is string.');
        }

        if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $attribute, $matches)) {
            throw new InvalidConfigException('The attribute must be specified in the type of "attribute", "attribute:format:label"');
        }

        self::$attribute = $matches[1];
        self::$format = isset($matches[3]) ? $matches[3] : 'text';
        self::$label = isset($matches[5]) ? $matches[5] : null;

        self::$model = $model;

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
}