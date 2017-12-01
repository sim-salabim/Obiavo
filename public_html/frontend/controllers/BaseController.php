<?php
namespace frontend\controllers;

use common\models\libraries\AdsSearch;
use Yii;
use frontend\helpers\TextHelper;
use yii\helpers\Url;

class BaseController extends \yii\web\Controller {

    protected $seo_title;
    protected $seo_h1;
    protected $seo_h2;
    protected $seo_text;
    protected $seo_desc;
    protected $seo_keywords;

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

    /**
     * @param array $ads_list array [
     * 'items' - список обьявлений,
     * 'count' - количество обьявлений попавших под выборку(не учитывая параметров пагинации),
     * 'price_range' - массив с мин и макс ценой в выборке ['min', 'max'],
     * 'views_amount' - общее количество просмотров обьявлений попавших под выборку,
     * 'finished_deals' - количество завершенных сделок попавших под выборкку]
     */
    protected function switchSeoKeys(Array $ads_list){
        $location = Yii::$app->location->country;
        if(Yii::$app->location->city){
            $location = Yii::$app->location->city;
        }else if(Yii::$app->location->region){
            $location = Yii::$app->location->region;
        }
        $keys_arr = [
            '{key:ads-amount}',
            '{key:price-from}',
            '{key:location}',
            '{key:site}',
            '{key:location-in}',
            '{key:location-of}',
            '{key:price-range}',
            '{key:count-views}',
            '{key:count-finished-deals}',
            '{key:count-proposals}',
            '{key:location-pp}',
        ];
        $replace_arr = [
            countString($ads_list['count'], [__('one_ad'), __('two_ads'), __('more_ads')]),
            ($ads_list['price_range']['min']) ? __('price from')." ".$ads_list['price_range']['min'] : __('price not defined'),
            $location->_text->name,
            Yii::$app->location->country->domain,
            __('in')." ".$location->_text->name_rp,
            $location->_text->name_pp,
            ($ads_list['price_range']['min'] and $ads_list['price_range']['max'] ) ? __('prices from')." ".$ads_list['price_range']['min']." ".__('_to')." ".$ads_list['price_range']['max'] : __('prices not defined'),
            countString($ads_list['views_amount'], [__('one_view'), __('two_views'), __('more_views')]),
            countString($ads_list['finished_deals'], [__('one finished deal'), __('two finished deals'), __('more finished deals'),]),
            countString($ads_list['count'], [__('one_ad'), __('two_ads'), __('more_ads')]),
            $location->_text->name_pp,
        ];
        $this->seo_title = str_replace($keys_arr, $replace_arr, $this->seo_title);
        $this->seo_h1 = str_replace($keys_arr, $replace_arr, $this->seo_h1);
        $this->seo_h2 = str_replace($keys_arr, $replace_arr, $this->seo_h2);
        $this->seo_keywords = str_replace($keys_arr, $replace_arr, $this->seo_keywords);
        $this->seo_desc = str_replace($keys_arr, $replace_arr, $this->seo_desc);
        $this->seo_text = str_replace($keys_arr, $replace_arr, $this->seo_text);
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