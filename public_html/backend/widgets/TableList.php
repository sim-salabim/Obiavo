<?php
namespace backend\widgets;
/*
 * Виджет админской таблицы для вывода в нем данных
 *
 * ---php---
 * 'data' => $objectsArray,
 * 'columns' = [
 *
 *      'attributes'    => 'ID',
 *      'model'         => $object,
 *      'label'         => 'Заголовок',
 *      'format'        => TableList::TYPE_TEXT,
 * ];
 *
 * OR
 *
 * $columns = [
 *      'model'         => $object,
 *      'content'       => function($data){ return $model->ID },
 *      'label'         => 'Заголовок',
 *      'format'        => 'text',
 * ];
 *
 */
use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TableList extends Widget{

    // data provider
    public $title = '';
    public $data = null;
    public $columns = [];

    const TYPE_TEXT = 'text';
    const TYPE_LINK = 'link';
    const TYPE_IMG  = 'image';
    const TYPE_MULTI_BUTTON  = 'multiButton';
    // кнопки управления (редактировать, удалить)
    const TYPE_OPT_BUTTON  = 'optButton';

    public function getViewPath()
    {
        return \Yii::getAlias('@app/widgets/views/table-list');
    }

    public function run(){

        $title = $this->title;

        $items = [];

        $items['label'] = $this->setHeaderItems($items);

//        foreach ($this->data as $object){
        $items['content'] = $this->setContentItems();
//        }

        return $this->render('index', compact('items','title'));
    }

    protected function setContentItems(){

        $itemsContent = [];
        foreach ($this->data as $object){
            $items = [];

            foreach($this->columns as $column){
                $value = '';
                $model = isset($column['model']) ? $column['model'] : $object;
                $format = \frontend\helpers\ArrayHelper::getValue($column, 'format',null);

                if (isset($column['attribute'])){

                    $attribute = $column['attribute'];

                    $value = \frontend\helpers\ArrayHelper::getValue($model, $attribute);

                } elseif (isset($column['content'])){
                    $content = $column['content'];
                    if ($content instanceof \Closure){
                        $value = call_user_func($content, $model);
                    } else {
                        $value = $content;
                    }
                }

                $value = isset($format) ? $this->{$format}($column,$model,$value) : $value;

    //            $this->contentFormatter($column);

                $items[] = $value;
            }

            $itemsContent[] = $items;
        }

        return $itemsContent;
    }

    protected function setHeaderItems(&$items){
        $itemsLabel = [];

        foreach ($this->columns as $column){

            $itemsLabel[] = $column['label'];
        }

        return $itemsLabel;
    }

    /**
     * Контент в зависимости от поля format
     */
    protected function formatter($column){
        \yii\grid\GridView::widget();
        $format = \frontend\helpers\ArrayHelper::getValue($column, 'format',null);

        if (!isset($format)){
            return;
        }

        $value = isset($format) ? $this->{$format}($model,$value) : $value;

    }

    protected function multiButton($column,$model,$value){
        $languages = \common\models\Language::getAllLanguages(true);
        $languagesModel = $model->_texts;

        return $this->render('multi-button',
                            compact('model',
                                    'languages',
                                    'languagesModel',
                                    'value'));
    }

    /**
     * Кнопки управления
     *
     * ```php
     * buttons = [
     *             'update',
     *             'delete',
     *             'custom' => function($model){return '';}
     * ];
     */
    protected function optButton($column,$model,$value){
        $buttons = \frontend\helpers\ArrayHelper::getValue($column, 'buttons',[]);
        $contentBut = '';

        foreach ($buttons as $but){
            if ($but instanceof \Closure){
                $contentBut .= call_user_func($but, $model);
                continue;
            }

            switch($but){
                case 'update':
                    $contentBut .= $this->render('opt-update-button', compact('model'));
                    break;
                case 'delete':
                    $contentBut .= $this->render('opt-delete-button', compact('model'));
                    break;
                case 'moderate':
                    $contentBut .= $this->render('opt-moderate-button', compact('model'));
                    break;
                    case 'unpublish':
                    $contentBut .= $this->render('opt-unpublish-button', compact('model'));
                    break;
                case 'update-seo-attached':
                    $contentBut .= $this->render('opt-update-seo-attached-button', compact('model'));
                    break;
                case 'run':
                    $contentBut .= $this->render('opt-run-button', compact('model'));
                    break;
            }
        }

        return $contentBut;
    }

}