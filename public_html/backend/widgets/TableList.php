<?php
namespace backend\widgets;
/*
 * Виджет админской таблицы для вывода в нем данных
 */
use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TableList extends Widget{
    public function functionName($param) {
        \yii\grid\GridView::widget();
    }
}