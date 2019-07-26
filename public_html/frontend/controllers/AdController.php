<?php
namespace frontend\controllers;

use common\models\AddApplicationText;
use common\models\Ads;
use common\models\AdsView;
use common\models\AutopostingTasks;
use common\models\CategoriesText;
use common\models\Category;
use common\models\City;
use common\models\CounterCategory;
use common\models\CounterCityCategory;
use common\models\Country;
use common\models\Language;
use common\models\libraries\AdsSearch;
use common\models\Placement;
use common\models\PlacementsText;
use common\models\Region;
use common\models\Settings;
use frontend\components\Location;
use frontend\helpers\LocationHelper;
use frontend\models\LoginForm;
use frontend\models\NewAdForm;
use Yii;
use yii\helpers\Url;
use yii\web\HttpException;

class AdController extends BaseController
{
    public $params;
    protected $seo_title;
    protected $seo_h1;
    protected $seo_h2;
    protected $seo_text;
    protected $seo_text1;
    protected $seo_text2;
    protected $seo_text3;
    protected $seo_text4;
    protected $seo_text5;
    protected $seo_text6;
    protected $seo_text7;
    protected $seo_desc;
    protected $seo_keywords;
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**  Страница добавления обьявления
     * @return string|\yii\web\Response
     */
    public function actionNewAdd(){
        $url = Yii::$app->request->get('url');
        $city = Yii::$app->request->get('city');
        $this->setApplicationUrl($url);
        $current_domain = Location::getCurrentDomain();
        $place_name_rp = "";
        $place_name_rp = "";
        $place_name = "";
        $canonical_city = '';
        if($city){//если мы находимся в городе или регионе
            $place = City::find()->where(['domain' => Yii::$app->request->get('city')])->one();
            if($place and Yii::$app->location->country->id != $place->region->country->id){
                throw new HttpException(404, 'Not Found');
            }
            if(!$place){
                $place = Region::find()->where(['domain' => Yii::$app->request->get('city')])->one();
                if($place and Yii::$app->location->country->id != $place->country->id){
                    throw new HttpException(404, 'Not Found');
                }
            }
            if($city != LocationHelper::getCurrentDomain()){
                City::setCookieLocation($city);
            }
            if($city){
                $this->setUrlForLogo($city);
            }
            $canonical_city = "$city/";
        }else{//если мы не находимся ни в городе ни в регионе
            $place = Country::find()->where(['domain' => $current_domain])->one();
        }
        $place_name_rp = __('in')." ".$place->_text->name_rp;
        $place_name_pp = $place->_text->name_pp;
        $place_name = $place->_text->name;
        $text = AddApplicationText::find()->where(["languages_id" => Language::getId(), 'url' => $url])->one();
        if(!$text){
            $url_part = str_replace(Ads::DEFAULT_LINK_RU."-","",$url );
            if($url_part != $url){
                $pl_app_url = PlacementsText::find()->where(['application_url' => $url_part, 'languages_id' => Language::getId()])->one();
                if($pl_app_url){
                    $text = AddApplicationText::find()->where(['placements_default' => 1, 'languages_id' => Language::getId()])->one();
                }else{
                    $text = CategoriesText::find()->where(['application_url' => $url_part, 'languages_id' => Language::getId()])->one();
                }
            }
            if(!$text) {
                $text = AddApplicationText::find()->where(['url' => Ads::DEFAULT_LINK_RU, 'languages_id' => Language::getId()])->one();
            }
        }
        if(!$text) throw new HttpException(404, 'Not Found');
        $this->seo_title = str_replace( ['{key:location-in}', '{key:site}'], [$place_name_rp, $current_domain],$text->seo_title);
        $this->seo_h1 = str_replace( ['{key:location-in}', '{key:site}'], [$place_name_rp, $current_domain],$text->seo_h1);
        $this->seo_desc = str_replace( ['{key:location-in}', '{key:site}'], [$place_name_rp, $current_domain],$text->seo_desc);
        $this->seo_keywords = str_replace( ['{key:location-in}', '{key:site}'], [$place_name_rp, $current_domain],$text->seo_keywords);
        $this->seo_text = str_replace( ['{key:location-in}', '{key:site}'], [$place_name_rp, $current_domain],$text->seo_text);
        $this->seo_text1 = $text->seo_text1 = str_replace( ['{key:location-in}', '{key:site}', '{key:location-of}', '{key:location}'], [$place_name_rp, $current_domain, $place_name_pp, $place_name],$text->seo_text1);
        $this->seo_text2 = $text->seo_text2 = str_replace( ['{key:location-in}', '{key:site}', '{key:location-of}', '{key:location}'], [$place_name_rp, $current_domain, $place_name_pp, $place_name],$text->seo_text2);
        $this->seo_text3 = $text->seo_text3 = str_replace( ['{key:location-in}', '{key:site}', '{key:location-of}', '{key:location}'], [$place_name_rp, $current_domain, $place_name_pp, $place_name],$text->seo_text3);
        $this->seo_text4 = $text->seo_text4 = str_replace( ['{key:location-in}', '{key:site}', '{key:location-of}', '{key:location}'], [$place_name_rp, $current_domain, $place_name_pp, $place_name],$text->seo_text4);
        $this->seo_text5 = $text->seo_text5 = str_replace( ['{key:location-in}', '{key:site}', '{key:location-of}', '{key:location}'], [$place_name_rp, $current_domain, $place_name_pp, $place_name],$text->seo_text5);
        $this->seo_text6 = $text->seo_text6 = str_replace( ['{key:location-in}', '{key:site}', '{key:location-of}', '{key:location}'], [$place_name_rp, $current_domain, $place_name_pp, $place_name],$text->seo_text6);
        $this->seo_text7 = $text->seo_text7 = str_replace( ['{key:location-in}', '{key:site}', '{key:location-of}', '{key:location}'], [$place_name_rp, $current_domain, $place_name_pp, $place_name],$text->seo_text7);
        $library_search = new AdsSearch();
        $library_search->setActive(true);
        $list = Ads::getList($library_search);
        $this->switchSeoKeys($list);
        $canonical_link = Url::home(true) . $canonical_city.$url."/";
        $this->setSeo($this->seo_h1, $this->seo_h2, $this->seo_text, $this->seo_desc, $this->seo_keywords,$canonical_link);
        $this->setPageTitle($this->seo_title);
        $categories = Category::find()
            ->where(['parent_id' => NULL, 'active'=>1])
            ->orderBy('order ASC, brand ASC, techname ASC')
            ->withText(['languages_id' => Language::getId()])
            ->all();
        $limit = Settings::find()->one()->categories_limit;
        $user = (Yii::$app->user->isGuest) ? null : Yii::$app->user->identity;
        $placements = Placement::find()->withText(['languages_id' => Language::getId()])->all();
        $breadcrumbs = [];
        if($city){
            $breadcrumbs[] = [
                'label' => $place->_text->name,
                'link' => "/",
                'use_cookie' => true,
                'title' => __('Free ads in ').$place->_text->name_rp,
                'city' => $place->domain
                ];
        }
        $breadcrumbs[] = ['label' => __('Publish an add'), 'use_cookie' => true, 'is_active' => false];
        Yii::$app->view->params['publish_page_hr'] = true;
        Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs($breadcrumbs, true, null);
        return $this->render('new', [
            'user' => $user,
            'categories_limit' => $limit,
            'placements' => $placements,
            'categories' => $categories,
            'text' => $text,
        ]);
    }

    /** Сохраняет новое обЬявление, обробатывая post-запрос
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        $model = new NewAdForm();
        if (Yii::$app->request->isPost){
            // если инпут со сроком действия задизейблен, то сделаем +месяц
            $_POST['expiry_date'] = !isset($_POST['expiry_date']) ? 2592000 : $_POST['expiry_date'];
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(isset($_POST['phone'])){
                if($_POST['phone'] == ""){
                    $_POST['phone'] = "+";
                }
            }
            if(isset($_POST['name'])){
                if($_POST['name'] == ""){
                    $_POST['name'] = "+";
                }
            }
            if(isset($_POST['email'])){
                if($_POST['email'] == ""){
                    $_POST['email'] = "+";
                }
            }
            if(isset($_POST['expiry_date']) and $_POST['expiry_date'] == "0"){
                unset($_POST['expiry_date']);
            }

            $model->load(Yii::$app->request->post(), '');
            $model->cities_id = $model->cities_id;
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $return = [];
            if(!$model->validate()) {
                $errors = $model->getErrors();
                $return['message'] = NewAdForm::MESSAGE_FAILED;
                $return['errors'] = (array)$errors;
                $return['model'] = (array)$model;
                return $return;
            }else{
                $return['message'] = NewAdForm::MESSAGE_SUCCESS;
                $model = $model->newAd();
                if(!Yii::$app->user->identity){
                    $return['session_token'] = $model->session_token;
                }
                $return['url'] = "$model->url";
                return $return;
            }
        } else {
            return $this->render('podat-obiavlenie');
        }
    }

    public function actionView(){
        $ad_url = Yii::$app->request->get('adUrl');
        $city = Yii::$app->request->get('city');
        //TODO проверка есть ли домен
        $ad = Ads::find()->where(['url' => $ad_url])->one();
        if(($ad->only_locally AND ($ad->city->domain != $city)) OR (!$ad->only_locally AND $city)) throw new HttpException(404, 'Not Found');
        if($ad->city->region->country->id != Yii::$app->location->country->id) throw new HttpException(404, 'Not Found');
        $ad_title = $ad->title." - ".__('ads in')." ".$ad->city->_text->name_rp." ".__('on the site')." ".ucfirst(Yii::$app->location->country->domain);
        $this->setPageTitle($ad_title);
        Yii::$app->view->params['canonical'] = Url::home(true) . $ad_url . "/";
        $breadcrumbs = $ad->getBreadcrumbs();
        $this->setUrlForLogo($ad->city->domain);
        Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs($breadcrumbs, false, $ad->city->domain);
        Yii::$app->view->params['seo_h1'] = $ad->title;
        Yii::$app->view->params['seo_desc'] = $ad->text;
        AdsView::eraseView($ad->id, Yii::$app->user->id);
        return $this->render('view', [
            'ad'   => $ad,
//            'show_phone_number' => (Yii::$app->request->get('show_phone_number') AND time() < $ad->expiry_date) ? Yii::$app->request->get('show_phone_number') : null,
            'show_phone_number' => (Yii::$app->request->get('show_phone_number')) ? Yii::$app->request->get('show_phone_number') : null,
            'user' => Yii::$app->user->identity,
        ]);
    }

    /**
     * Редактирование обьявления
     */
    public function actionEdit(){
        $ad_url = Yii::$app->request->get('adUrl');
        $ad = Ads::find()->where(['url' => $ad_url])->one();
        $current_user = Yii::$app->user->identity;
        if(
            !$ad or
            (!$current_user and (!isset($_COOKIE['session_token']) or $_COOKIE['session_token'] != $ad->session_token)) or
            ($current_user and $ad and $ad->users_id != $current_user->id and !$current_user->is_admin)
        ){
            throw new HttpException(404, 'Not Found');
        }
        $this->seo_title = $this->seo_h1 = __('Ad editing'). " \"".$ad->title."\"";
        $this->setSeo($this->seo_h1, $this->seo_h2, $this->seo_text, $this->seo_desc, $this->seo_keywords);
        $this->setPageTitle($this->seo_title);
        $categories = Category::find()
            ->where(['parent_id' => NULL, 'active'=>1])
            ->orderBy('order ASC, brand ASC, techname ASC')
            ->withText(['languages_id' => Language::getDefault()->id])
            ->all();
        $limit = Settings::find()->one()->categories_limit;
        $placements = Placement::find()->all();
        return $this->render('new', [
            'user'=>$current_user,
            'categories'=>$categories,
            'categories_limit'=>$limit,
            'placements'=>$placements,
            'ad'=>$ad
            ]);
    }

    public function actionEditAdd(){
        if (Yii::$app->request->isPost){

            // если инпут со сроком действия задизейблен, то сделаем +месяц
            $_POST['expiry_date'] = !isset($_POST['expiry_date']) ? 2592000 : $_POST['expiry_date'];
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model = new NewAdForm();
            $model->load(Yii::$app->request->post(), '');
            $model->cities_id = $model->cities_id;
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $ad = Ads::find()->where(['id'=>$_POST['id']])->one();
            if(!$ad){
                Yii::$app->response->statusCode = 404;
                $return['errors'] = (array)'Обьявление не найдено';
                return  $return;
            }

            foreach($ad->files as $ad_file){
                if(!in_array($ad_file->id, $_POST['files'])){
                    $ad_file->deleteFile();
                }
            }
            $return = [];
            if(!$model->validate()) {
                $errors = $model->getErrors();
                $return['message'] = NewAdForm::MESSAGE_FAILED;
                $return['errors'] = (array)$errors;
                $return['model'] = (array)$model;
                return $return;
            }else{
                $return['message'] = NewAdForm::MESSAGE_SUCCESS;

                $model = $model->newAd();
                $return['url'] = "$model->url";
                return $return;
            }
        } else {
            return $this->render('podat-obiavlenie');
        }
    }

    /** Используется для поиска обьявления
     * @return string
     */
    public function actionSearch(){
        $sort = Yii::$app->request->get('sort');
        $direction = Yii::$app->request->get('direction');
        $query = Yii::$app->request->get('query');
        $page = Yii::$app->request->get('page') ?: 1;

        $this->setPageTitle(__('Search'));
        Yii::$app->view->params['breadcrumbs'] = [];
        Yii::$app->view->params['h1'] = __('Search');
        $librarySearch = new AdsSearch();
        $librarySearch->setQuery($query);
        $librarySearch->setPage($page);
        $librarySearch->setActive(true);
        if($sort AND $direction) {
            $librarySearch->setSorting($sort." ".$direction);
        }
        return $this->render('search',  [
            'library_search' => $librarySearch
        ]);
    }

    public function actionNewAddLogin(){
        if(Yii::$app->request->isPost){
            $model = new LoginForm();
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post(),'');
            if ($model->login()) {
                return $this->redirect('/podat-obiavlenie/');
            } elseif(!$model->validate()) {
                $errors = $model->getErrors();
                foreach($errors as $key => $item){
                    \Yii::$app->getSession()->setFlash($key.'_error', $item[0]);
                }
                \Yii::$app->getSession()->setFlash('model', $model);
                return $this->redirect('/podat-obiavlenie/');
            }
        }else{
            return $this->redirect('/podat-obiavlenie/');
        }
    }

    public function actionRaise(){
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ad = Ads::find()->where(['id'=>$post['id']])->one();
        if(!$ad OR (time() - $ad->updated_at) < 86400){
            return "error";
        }
        $ad->updated_at = time();
        $ad->save();
        return $ad->id;
    }

    public function actionDeactivate(){
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ad = Ads::find()->where(['id'=>$post['id']])->one();
        if(!$ad){
            return "error";
        }
        $ad->active = 0;
        $ad->updated_at = time();
        $ad->save();
        $ids = $ad->getAllCategoriesIds();
        foreach($ids as $id){
            $category_counter = CounterCategory::find()->where([
                'categories_id' => $id,
                'countries_id' => $ad->city->region->country->id
                ])->one();
            $category_counter->ads_amount = $category_counter->ads_amount - 1;
            $category_counter->save();

            $category_city_counter = CounterCityCategory::find()->where(['cities_id' => $ad->city->id, 'categories_id' => $id])->one();
            $category_city_counter->ads_amount = $category_city_counter->ads_amount - 1;
        }
        $city_counter = City::find()->where(['id' => $ad->city->id])->one();
        $city_counter->ads_amount = $city_counter->ads_amount - 1;
        $city_counter->save();
        return $ad->id;
    }

    public function actionRepost(){
        if(!Yii::$app->request->isPost) throw new HttpException(404, 'Not Found');
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ad = Ads::find()->where(['id'=>$post['id']])->one();
        if(!$ad){
            return "error";
        }
        $ad->active = 1;
        $ad->updated_at = time();
        $ad->expiry_date = time() + 2592000;
        $ad->save();
        $ids = $ad->getAllCategoriesIds();
        foreach($ids as $id){
            $category_counter = CounterCategory::find()->where([
                'categories_id' => $id,
                'countries_id' => $ad->city->region->country->id
            ])->one();
            $category_counter->ads_amount = $category_counter->ads_amount + 1;
            $category_counter->save();

            $category_city_counter = CounterCityCategory::find()->where(['cities_id' => $ad->city->id, 'categories_id' => $id])->one();
            $category_city_counter->ads_amount = $category_city_counter->ads_amount + 1;
        }
        $city_counter = City::find()->where(['id' => $ad->city->id])->one();
        $city_counter->ads_amount = $city_counter->ads_amount + 1;
        $city_counter->save();
        return $ad->getHumanDate(Ads::DATE_TYPE_EXPIRATION);
    }
}