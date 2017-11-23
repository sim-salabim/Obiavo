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

    public function setSeo($seo_h1, $seo_h2, $seo_text, $seo_desc, $seo_keywords, $canonical = null){
        Yii::$app->view->params['seo_h1'] = $seo_h1;
        Yii::$app->view->params['seo_h2'] = $seo_h2;
        Yii::$app->view->params['seo_text'] = $seo_text;
        Yii::$app->view->params['seo_desc'] = $seo_desc;
        Yii::$app->view->params['seo_keywords'] = $seo_keywords;
        Yii::$app->view->params['canonical'] = $canonical;
    }

    public function setBreadcrumbs($array = [], $show_last_one = false){
        $breadcrumbs = [['label' => __('Home page'), 'link' => URL::to(Yii::$app->homeUrl)]];
        if(!empty($array)){
            foreach($array as $item){
                $breadcrumbs[] = $item;
            }
        }
        if(!$show_last_one) array_pop($breadcrumbs);
        return $breadcrumbs;
    }

    public function beforeAction(){
        return $this->setPageTitle();
    }
}