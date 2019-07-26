<?php
namespace frontend\controllers;

use common\models\Country;
use common\models\libraries\AdsSearch;
use frontend\components\Location;
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
    protected $location_domain;
    protected $country;

    /**
     * @param null $title
     * @return \frontend\helpers\type|string
     */
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

    /**
     * @param $ads_search[], массив , результат работы Ads::getList()
     * @param AdsSearch $library_search , настроенный обьект
     * @param $current_page, текущая страница
     */
    public function setNextAndPrevious($ads_search, AdsSearch $library_search, $current_page){
        $pages_amount = ceil(($ads_search['count'] / $library_search->limit));
        $url = Url::home(true).substr(Yii::$app->request->getPathInfo(), 0);
        if($pages_amount > 1 AND count($ads_search['items']) > 0){
            if($current_page == 1){
                Yii::$app->view->params['next'] = $url."?page=2";
            }else if($current_page == $pages_amount){
                Yii::$app->view->params['prev'] = $url."?page=".($current_page - 1);
            }elseif($current_page == 2){
                Yii::$app->view->params['next'] = $url."?page=".($current_page + 1);
            }else{
                Yii::$app->view->params['prev'] = $url."?page=".($current_page - 1);
                Yii::$app->view->params['next'] = $url."?page=".($current_page + 1);
            }
        }
    }

    /** Задает урл для кнопки "подать обьявление" в шапке сайта
     *
     * @param $application_url
     */
    public function setApplicationUrl($application_url){
        Yii::$app->view->params['application_url'] = "/".$application_url."/";
    }

    /**
     * @param $seo_h1
     * @param $seo_h2
     * @param $seo_text
     * @param $seo_desc
     * @param $seo_keywords
     * @param null $canonical
     */
    public function setSeo($seo_h1, $seo_h2, $seo_text, $seo_desc, $seo_keywords, $canonical = null){
        Yii::$app->view->params['seo_h1'] = $seo_h1;
        Yii::$app->view->params['seo_h2'] = $seo_h2;
        Yii::$app->view->params['seo_text'] = $seo_text;
        Yii::$app->view->params['seo_desc'] = $seo_desc;
        Yii::$app->view->params['seo_keywords'] = $seo_keywords;
        Yii::$app->view->params['canonical'] = $canonical;
    }

    public function setUrlForLogo($location_domain){
        Yii::$app->view->params['location_domain'] = "/".$location_domain."/";
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
            '{key:count-ads}',
            '{key:price-from}',
            '{key:location}',
            '{key:site}',
            '{key:location-in}',
            '{key:location-of}',
            '{key:prices-range}',
            '{key:count-views}',
            '{key:count-finished-deals}',
            '{key:count-proposals}',
            '{key:location-pp}',
            '{key:count-deals}',
            '{key:count-deals-made}',
            '{key:count-items-views}',
            '{key:slr-from}',
            '{key:slr-range}',
            '{key:salary-range}',
        ];
        $currency_name = Yii::$app->location->country->currency->_text->name_short.".";
        $replace_arr = [
           /*{key:count-ads}*/countString($ads_list['count'], [__('one_ad'), __('two_ads'), __('more_ads')]),
            /*{key:price-from}*/($ads_list['price_range']['min']) ? __('price from')." ".$ads_list['price_range']['min']." ".$currency_name : __('price not defined'),
            /*{key:location}*/$location->_text->name,
            /*{key:site}*/ucfirst(Yii::$app->location->country->domain),
            /*{key:location-in}*/__('in')." ".$location->_text->name_rp,
            /*{key:location-of}*/$location->_text->name_pp,
            /*{key:prices-range}*/($ads_list['price_range']['min'] and $ads_list['price_range']['max'] ) ? __('prices from')." ".$ads_list['price_range']['min']." ".$currency_name." ".__('_to')." ".$ads_list['price_range']['max']." ".$currency_name : __('prices not defined'),
            /*{key:count-views}*/countString($ads_list['views_amount'], [__('one_view'), __('two_views'), __('more_views')]),
            /*{key:count-finished-deals}*/countString($ads_list['finished_deals'], [__('one finished deal'), __('two finished deals'), __('more finished deals'),]),
            /*{key:count-proposals}*/countString($ads_list['count'], [__('one_proposal'), __('two_proposals'), __('more_proposals')]),
            /*{key:location-pp}*/$location->_text->name_pp,
            /*{key:count-deals}*/countString($ads_list['finished_deals'], [__('one_deal'), __('two_deals'), __('more_deals'),]),
            /*{key:count-deals-made}*/countString($ads_list['finished_deals'], [__('one_deal_made'), __('two_deals_made'), __('more_deals_made'),]),
            /*{key:count-items-views}*/countString($ads_list['views_amount'], [__('one_items_view'), __('two_items_views'), __('more_items_views')]),
            /*{key:slr-from}*/($ads_list['price_range']['min']) ? __('slr_from')." ".$ads_list['price_range']['min']." ".$currency_name : __('slr not defined'),
            /*{key:slr-range}*/($ads_list['price_range']['min'] and $ads_list['price_range']['max'] ) ? __('slr_from')." ".$ads_list['price_range']['min']." ".$currency_name." ".__('_to')." ".$ads_list['price_range']['max']." ".$currency_name : __('slr not defined'),
            /*{key:salary-range}*/($ads_list['price_range']['min'] and $ads_list['price_range']['max'] ) ? __('salary from')." ".$ads_list['price_range']['min']." ".$currency_name." ".__('_to')." ".$ads_list['price_range']['max']." ".$currency_name : __('slr not defined'),
        ];
        $this->seo_title = str_replace($keys_arr, $replace_arr, $this->seo_title);
        $this->seo_h1 = str_replace($keys_arr, $replace_arr, $this->seo_h1);
        $this->seo_h2 = str_replace($keys_arr, $replace_arr, $this->seo_h2);
        $this->seo_keywords = str_replace($keys_arr, $replace_arr, $this->seo_keywords);
        $this->seo_desc = str_replace($keys_arr, $replace_arr, $this->seo_desc);
        $this->seo_text = str_replace($keys_arr, $replace_arr, $this->seo_text);
    }

    /**
     * @param array $array[label => '', link =>'']
     * @param bool $show_last_one
     * @param string $location_domain
     * @return array
     */
    public function setBreadcrumbs($array = [], $show_last_one = false, $location_domain = null){
        $home_link = $location_domain ? $location_domain."/" : Url::toRoute(['/']);
        $use_cookie = $location_domain ? false : true;
        $breadcrumbs = [['label' => __('Home page'), 'link' => $home_link, 'title'=> __('Free ads Obiavo'), 'use_cookie' => $use_cookie]];
        if(!empty($array)){
            foreach($array as $item){
                $breadcrumbs[] = $item;
            }
        }
        if(!$show_last_one) array_pop($breadcrumbs);
        return $breadcrumbs;
    }

    public function beforeAction(){
        Yii::$app->language = Yii::$app->location->country->language->code;
        return $this->setPageTitle();
    }

}