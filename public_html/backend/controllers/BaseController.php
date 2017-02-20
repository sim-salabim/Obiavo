<?php
namespace backend\controllers;

use Yii;
/*
 * Компонент базового контроллера
 * Создавался для того, чтобы разработчик при рендере за задумывался, что ему надо отдать, весь контент, либо часть контейнера
 * Но в будущем базовый контроллер даст больше гибкости
 */
class BaseController extends \yii\web\Controller {
    /**
     * Обычно при ajax запросе требуется вернуть только запрашиваемый view
     * этот параметр позволит вернуть весь контент сайта, если нужно
     * @var bool
     */
    public $fullContentForAjax = false;


    public function render($view,$params = []){
        if (Yii::$app->request->isAjax && !$this->fullContentForAjax){
            return parent::renderAjax($view,$params);
        }

        return parent::render($view,$params);
    }

    public function renderPartial($view , $params = []){
        if (Yii::$app->request->isAjax && !$this->fullContentForAjax){
            return parent::renderAjax($view,$params);
        }

        return parent::renderPartial($view,$params);
    }

    public function sendJsonData($jsonDataArray){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return \common\helpers\JsonData::current($jsonDataArray);
    }

    public function isJson(){
        if (isset($_REQUEST['json']) && $_REQUEST['json'] === 'true'){
            return true;
        }

        return false;
    }
}