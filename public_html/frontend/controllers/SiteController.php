<?php
namespace frontend\controllers;

use common\models\Ads;
use common\models\City;
use common\models\CityOrder;
use common\models\Cms;
use common\models\Country;
use common\models\Language;
use common\models\libraries\AdsSearch;
use common\models\LoginForm;
use common\models\Region;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use yii\helpers\Url;
use Yii;
use yii\base\InvalidParamException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;


class SiteController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $domain = Yii::$app->request->get('domain');
        $root_url = "";
        if($domain){
            $city = City::find()->where(['domain' => $domain])->one();
            if($city and $city->region->country->id != Yii::$app->location->country->id){
                throw new HttpException(404, 'Not Found');
            }
            $region = Region::find()->where(['domain' => $domain])->one();
            if($region and $region->country->id != Yii::$app->location->country->id){
                throw new HttpException(404, 'Not Found');
            }
            City::setCookieLocation($domain);
            $root_url = $root_url.$domain."/";
        }

        $region = isset($_COOKIE['region']) ? $_COOKIE['region'] : null;
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : null;
        $place = Yii::$app->location->country;
        $region_add = '';
        if($region){
            $place = Yii::$app->location->region;
            $this->setUrlForLogo($region);
            $region_add = "/".Yii::$app->location->region->domain;
        }
        if($city){
            $place = Yii::$app->location->city;
            $this->setUrlForLogo($city);
            $region_add = "/".Yii::$app->location->city->domain;
        }
        $categories = \common\models\Category::find()
                            ->where(['active' => 1])
                            ->orderBy('order ASC, brand ASC, techname ASC')
                            ->withText(Language::getId())
                            ->withChildren()
                            ->orphan()
                            ->all();
        $country_id = Country::find()->select('id')->where(['domain' => Yii::$app->location->country->domain])->one()->id;
        $regions_ids = Region::find()->where(['countries_id' => $country_id])->asArray()->all();
        $regions_arr = [];
        foreach($regions_ids as $id){
            $regions_arr[] = $id['id'];
        }
        $cities = CityOrder::find()->withText()
            ->leftJoin('cities', 'cities.id = cities_order.cities_id')
            ->where(['in','cities.regions_id', $regions_arr])
            ->orderBy(['cities_order.order' => SORT_ASC])
            ->all();
        // достанем цмс страницу site-header чтоб установить сео элементы для главной страницы
        $cms_page = Cms::getByTechname('site-header');
        //настройка сео вещей
        $this->seo_title = $cms_page->_text->seo_title;
        $canonical = '';
        if($domain) {
            $canonical = $domain. '/';
        }
        $this->seo_title = $cms_page->_text->seo_title;
        $this->seo_text = $cms_page->_text->seo_text;
        $this->seo_h1 = $cms_page->_text->seo_h1;
        $this->seo_h2 = $cms_page->_text->seo_h2;
        $this->seo_desc = $cms_page->_text->seo_desc;
        $this->seo_keywords = $cms_page->_text->seo_keywords;
        $canonical_link = Url::home(true) . $canonical;
        $library_search = new AdsSearch();
        $library_search->setActive(true);
        $library_search->setConsiderLocation(true);
        $library_search->setAll(true);
        $page = (Yii::$app->request->get('page')) ? Yii::$app->request->get('page') : $library_search->page;
        $library_search->setPage($page);
        $sort = Yii::$app->request->get('sort');
        $direction = Yii::$app->request->get('direction');
        if($sort AND $direction) {
            $library_search->setSorting($sort." ".$direction);
        }
        $ads_model = new Ads();
        $ads_search = $ads_model->getList($library_search, true);
        if($page > 1){
            if(!count($ads_search['items'])) {
                $page = ceil(($ads_search['count'] / $library_search->limit));
                $library_search->page = $page;
                $ads_search = Ads::getList($library_search);
            }
            $this->seo_title .= " - ".__('Page')." $page";
            $this->seo_desc .= " - ".__('Page')." $page";
        }
        //если мы не на первой странице списка, то уберем сео-текст
        if($page > 1 or $sort or $direction){
            $this->seo_text = null;
        }
        if($sort or $direction){
            $seo_sort_str = " - ".__('Sorting')." ";
            switch($sort){
                case 'title':
                    $seo_sort_str .= __('by alphabet')." ";
                    break;
                case 'price':
                    $seo_sort_str .= __('by price')." ";
                    break;
                case 'created_at':
                    $seo_sort_str .= __('by date')." ";
            }
            switch($direction){
                case 'asc':
                    $seo_sort_str .= __('asc');
                    break;
                case 'desc':
                    $seo_sort_str .= __('desc');
                    break;
            }
            $this->seo_desc .= $seo_sort_str.".";
            $this->seo_title .= $seo_sort_str;
        }
        $show_cities_list = true;
        Yii::$app->view->params['no_hr'] = true;
        if($region or $city) {
            $breadcrumbs[] = [
                'label' => __('Baraholka')." ".$place->_text->name_pp,
                'link' => $place->domain. "/",
                'use_cookie' => true,
                'is_active' => false,
            ];
            Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs($breadcrumbs, true);
            Yii::$app->view->params['no_hr'] = false;
            $show_cities_list = false;
        }
        $this->switchSeoKeys($ads_search);
        Yii::$app->view->params['seo_desc'] = $this->seo_desc;
        Yii::$app->view->params['seo_keywords'] = $this->seo_keywords;
        Yii::$app->view->params['seo_h1'] = $this->seo_h1;
        Yii::$app->view->params['seo_h2'] = $this->seo_h2;
        Yii::$app->view->params['seo_text'] = str_replace('{key:application-url}', yii\helpers\Url::toRoute($region_add."/".\common\models\Ads::generateApplicationUrl()), $this->seo_text);
        Yii::$app->view->params['canonical'] = $canonical_link;
        $this->setPageTitle($this->seo_title);
        $seo_text = $this->seo_text;
        $country_amount = (new Query())->select('sum(ads_amount) as ads_amount')
            ->from('cities')
            ->where(["IN", 'regions_id', (new Query())->select('id')->from('regions')->where(['countries_id' => Yii::$app->location->country->id])])
            ->one();
        $this->setNextAndPrevious($ads_search, $library_search, $page);
        $page_pagination_title = "{page_num:key} ".__('of category').": ".__('free ads')." ".__('in')." ".$place->_text->name_rp;
//        print_r($this->params);exit;
        return $this->render('index',
            compact(
                'categories',
                'cities',
                'seo_text',
                'ads_search',
                'library_search',
                'country_amount',
                'page',
                'root_url',
                'page_pagination_title',
                'show_cities_list',
                'place'
            ));
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['commonAdminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionRedirect($href)
    {
        return Yii::$app->response->redirect($href);
    }

}
