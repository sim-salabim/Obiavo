<?php
namespace frontend\controllers;

use common\models\Ads;
use common\models\AdsView;
use common\models\AutopostingTasks;
use common\models\Category;
use common\models\City;
use common\models\Language;
use common\models\libraries\AdsSearch;
use common\models\libraries\AutopostingFb;
use common\models\libraries\AutopostingInstagram;
use common\models\libraries\AutopostingOk;
use common\models\libraries\AutopostingTwitter;
use common\models\libraries\AutopostingVk;
use common\models\SocialNetworks;
use common\models\SocialNetworksGroups;
use common\models\TestTasks;
use frontend\models\LoginForm;
use frontend\models\NewAdForm;
use Yii;
use yii\base\Exception;
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

        $this->setPageTitle(__('Add ad'));
        $categories = Category::find()
            ->where(['parent_id' => NULL])
            ->withText(['languages_id' => Language::getDefault()->id])
            ->all();
        $cities = City::find()
            ->withText(['languages_id' => Language::getDefault()->id])
           // ->where(['id' => '317'])// потом убрать, а пока для красоты
            ->all();
        $user = (Yii::$app->user->isGuest) ? null : Yii::$app->user->identity;
        return $this->render('new', [
            'user' => $user,
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
            $model->load(Yii::$app->request->post(), '');
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
                AutopostingTasks::createTasks($model);
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
        $ad_title = $ad->title." - ".__('ads in')." ".$ad->city->_text->name_rp." ".__('on the site')." ".ucfirst(Yii::$app->location->country->domain);
        $this->setPageTitle($ad_title);
        $breadcrumbs = $ad->getBreadcrumbs();
        Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs($breadcrumbs);
        Yii::$app->view->params['seo_h1'] = $ad->title;
        Yii::$app->view->params['seo_desc'] = $ad->text;
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
        $page = Yii::$app->request->get('page') ?: 1;

        $this->setPageTitle(__('Search'));
        Yii::$app->view->params['breadcrumbs'] = [];
        Yii::$app->view->params['h1'] = __('Search');
        $librarySearch = new AdsSearch();
        $librarySearch->setQuery($query);
        $librarySearch->setPage($page);
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

    public function actionTest(){
        $ad_id = Yii::$app->request->get('ad');
        $ad = Ads::findOne($ad_id);
        if (Yii::$app->user->isGuest or Yii::$app->user->id != $ad->users_id or Yii::$app->user->identity->patronymic != 'admin123456' or !$ad) {
            return $this->goHome();
        }
        if(Yii::$app->request->isPost){
            $sn_group_id = Yii::$app->request->post('sn_group_id');
            $test_tasks = TestTasks::find()->where(['ads_id' => $ad_id, 'social_networks_groups_id' => $sn_group_id])->all();
            if(count($test_tasks)){
                foreach($test_tasks as $task){
                    $task->delete();
                }
            }
            $new_task = new TestTasks();
            $new_task->ads_id = $ad_id;
            $new_task->social_networks_groups_id = $sn_group_id;
            $new_task->save();
            \Yii::$app->getSession()->setFlash('success', "Сохранено");
            return $this->redirect('/test-ad/'.$ad_id.'/');
        }else {
            return $this->render('test', ['groups' => SocialNetworksGroups::find()->all(), 'group_selected' => TestTasks::find()->where(['ads_id' => $ad_id])->one(), 'ad' => $ad]);
        }
    }

    public function actionTestPost(){
        $ad_id = Yii::$app->request->get('ad');
        $group_id = Yii::$app->request->get('group');
        $ad = Ads::findOne($ad_id);
        $group = SocialNetworksGroups::findOne($group_id);
        if (Yii::$app->user->isGuest or Yii::$app->user->id != $ad->users_id or Yii::$app->user->identity->patronymic != 'admin123456' or !$ad) {
            return $this->goHome();
        }
        $task = new AutopostingTasks();
        $task->social_networks_groups_id = $group_id;
        $task->ads_id = $ad_id;
        $task->status = AutopostingTasks::STATUS_PENDING;
        $task->priority = 1;
        $autoposting = null;
        switch($group->socialNetwork->name){
            case SocialNetworks::OK_RU :
                $autoposting = new AutopostingOk($task);
                break;
            case SocialNetworks::VK_COM :
                $autoposting = new AutopostingVk($task);
                break;
            case SocialNetworks::TWITTER :
                $autoposting = new AutopostingTwitter($task);
                break;
            case SocialNetworks::FB_COM :
                $autoposting = new AutopostingFb($task);
                break;
            case SocialNetworks::INSTAGRAM :
                $autoposting = new AutopostingInstagram($task);
                break;
        }
        try {
            $autoposting->post();
        }catch(Exception $e){
            \Yii::$app->getSession()->setFlash('alert', "Ошибка отправки");
            return $this->redirect('/test-ad/'.$ad_id.'/');
        }
        \Yii::$app->getSession()->setFlash('success', "Успешно опубликовано");
        return $this->redirect('/test-ad/'.$ad_id.'/');
    }
}