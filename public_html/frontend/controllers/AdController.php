<?php
namespace frontend\controllers;

use common\models\Ads;
use common\models\AdsView;
use common\models\Category;
use common\models\City;
use common\models\Language;
use common\models\libraries\AdsSearch;
use frontend\models\NewAdForm;
use Yii;
use yii\web\HttpException;

class AdController extends BaseController
{
    public $params;

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
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->setPageTitle(__('Add ad'));
        $categories = Category::find()
            ->where(['parent_id' => NULL])
            ->withText(['languages_id' => Language::getDefault()->id])
            ->all();
        $cities = City::find()
            ->withText(['languages_id' => Language::getDefault()->id])
            ->where(['id' => '317'])// потом убрать, а пока для красоты
            ->all();
        return $this->render('new', [
            'user' => Yii::$app->user->identity,
            'categories' => $categories,
            'cities' => $cities,
        ]);
    }

    /** Сохраняет новое обЬявление, обробатывая post-запрос
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new NewAdForm();
        if (Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post(),'');
            $model->cities_id = Yii::$app->user->identity->cities_id;// пока ид города оставим захардкоженным
            if(!$model->validate()) {
                $errors = $model->getErrors();
                foreach($errors as $key => $item){
                    \Yii::$app->getSession()->setFlash($key.'_error', $item[0]);
                }
                \Yii::$app->getSession()->setFlash('model', $model);
                return $this->redirect('/podat-obiavlenie/');
            }else{
                $model = $model->newAd();
                \Yii::$app->getSession()->setFlash('message', __('Add successfully added.'));
                return $this->redirect("/$model->url/");
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
        $this->setPageTitle($ad->title);
        $breadcrumbs = $ad->getBreadcrumbs();
        Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs($breadcrumbs);
        Yii::$app->view->params['h1'] = $ad->title;
        AdsView::eraseView($ad->id, Yii::$app->user->id);
        return $this->render('view', [
            'ad'   => $ad,
            'show_phone_number' => (Yii::$app->request->get('show_phone_number')) ? Yii::$app->request->get('show_phone_number') : null,
            'user' => Yii::$app->user->identity,
        ]);
    }

    public function actionSearch(){
        $sort = Yii::$app->request->get('sort');
        $direction = Yii::$app->request->get('direction');
        $query = Yii::$app->request->get('query');

        $this->setPageTitle(__('Search'));
        Yii::$app->view->params['breadcrumbs'] = [];
        Yii::$app->view->params['h1'] = __('Search');
        $librarySearch = new AdsSearch();
        $loaded = (Yii::$app->request->get('loaded')) ? Yii::$app->request->get('loaded') + $librarySearch->limit : $librarySearch->loaded;
        $librarySearch->setLimit($loaded);
        $librarySearch->setQuery($query);
        if($sort AND $direction) {
            $librarySearch->setSorting($sort." ".$direction);
        }
        return $this->render('search',  [
            'loaded'        => $loaded,
            'ads_search'    => (new Ads())->getList($librarySearch)
        ]);
    }
}