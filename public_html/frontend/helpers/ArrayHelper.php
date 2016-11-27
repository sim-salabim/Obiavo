<?php
namespace frontend\helpers;

use yii\helpers\ArrayHelper as AH;

class ArrayHelper extends AH {

    /**
     * Преобразует объект в массив с текущим переводом
     *
     * [$categories, 'categoriesText->name']
     */
    public static function make1Array($objects, $to){

        $result = [];

        foreach($objects as $object){

            $relationName = explode('->', $to)[0];
            $relationField = explode('->', $to)[1];
            $relatedRecord = $object->getRelatedRecords()[$relationName];

            if ($relatedRecord){
                $relationFieldValue = self::getValue($relatedRecord,$relationField);

            } elseif (empty($relatedRecord)){
                $relationFieldValue = $object->$relationName
                                    ? $object->$relationName->$relationField
                                    : 'Перевод отсутствует';

            }

            $result[] = $relationFieldValue;
        }

        return $result;
    }
}