<?php
namespace frontend\controllers;

use common\models\Ads;
use common\models\CityOrder;
use common\models\Cms;
use common\models\Country;
use common\models\libraries\AdsSearch;
use common\models\Region;
use frontend\components\Location;
use frontend\helpers\LocationHelper;
use MongoDB\Operation\Count;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;


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
        $url = str_replace('/','',Yii::$app->getRequest()->getUrl());
        if($url != LocationHelper::getCurrentDomain()){
            return $this->redirect(Url::toRoute('/'.LocationHelper::getCurrentDomain()));
        }
        $categories = \common\models\Category::find()
                            ->where(['active' => 1])
                            ->withText()
                            ->withChildren()
                            ->orphan()
                            ->all();
        Yii::$app->location->country;
        $country_id = Country::find()->select('id')->where(['domain' => Yii::$app->location->country])->one()->id;
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
        //настройка сое вещей
        $this->seo_title = $cms_page->_text->seo_title;
        $this->seo_text = $cms_page->_text->seo_text;
        $this->seo_h1 = $cms_page->_text->seo_h1;
        $this->seo_h2 = $cms_page->_text->seo_h2;
        $this->seo_desc = $cms_page->_text->seo_desc;
        $this->seo_keywords = $cms_page->_text->seo_keywords;
        $librarySearch = new AdsSearch();
        $ads_model = new Ads();
        $ads_list = $ads_model->getList($librarySearch, false);
        $this->switchSeoKeys($ads_list);
        Yii::$app->view->params['seo_desc'] = $this->seo_desc;
        Yii::$app->view->params['seo_keywords'] = $this->seo_keywords;
        Yii::$app->view->params['seo_h1'] = $this->seo_h1;
        Yii::$app->view->params['seo_h2'] = $this->seo_h2;
        Yii::$app->view->params['seo_text'] = $this->seo_text;
        Yii::$app->view->params['no_hr'] = true;
        $this->setPageTitle($this->seo_title);
        $seo_text = $this->seo_text;
        return $this->render('index',  compact('categories','cities', 'seo_text'));
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
