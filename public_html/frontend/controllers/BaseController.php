<?php
namespace frontend\controllers;

use Yii;
use frontend\helpers\TextHelper;
use yii\helpers\Url;

class BaseController extends \yii\web\Controller {

    public function setPageTitle($title = null){

        if (!$title){
            return $this->view->title = TextHelper::pageTitle("Бесплатные объявления в {city}",['city' => Yii::$app->location->name_pp]);
        }

        if (is_object($title)){
            return $this->view->title = TextHelper::pageTitle("{$title->_text->name} в {city}",['city' => Yii::$app->location->name_pp]);
        }

        if(is_string($title)) {
            return $this->view->title = $title;
        }
    }

    public function setBreadcrumbs($array = []){
        $breadcrumbs = [['label' => __('Home page'), 'link' => URL::to(Yii::$app->homeUrl)]];
        if(!empty($array)){
            foreach($array as $item){
                $breadcrumbs[] = $item;
            }
        }
        return $breadcrumbs;
    }

    public function beforeAction(){
        return $this->setPageTitle();
    }
}