<?php
namespace backend\controllers;

use common\helpers\JsonData;
use common\models\AdCategory;
use common\models\Ads;
use common\models\AutopostingTasks;
use common\models\Category;
use common\models\CategoryAd;
use common\models\City;
use common\models\CounterCategory;
use common\models\CounterCityCategory;
use common\models\Files;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class ModerationController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actionIndex(){
        $current_page = \Yii::$app->request->get('page') ? \Yii::$app->request->get('page') : 1;
        $limit = 20;
        $offset = ($current_page - 1) * $limit;
        $rows = Ads::find()->where(['moderated' => 0])->count();
        $pages_amount = ceil($rows/$limit);
        $ads = Ads::find()->where(['moderated' => 0])
            ->limit($limit)
            ->offset($offset)
            ->orderBy('id DESC')
            ->all();
        $link = "?";
        return $this->render('index',  compact('ads', 'pages_amount', 'current_page', 'link'));
    }

    public function actionModerated(){
        $current_page = \Yii::$app->request->get('page') ? \Yii::$app->request->get('page') : 1;
        $limit = 20;
        $offset = ($current_page - 1) * $limit;
        $rows = Ads::find()->where(['moderated' => 1])->count();
        $pages_amount = ceil($rows/$limit);
        $ads = Ads::find()->where(['moderated' => 1])
            ->limit($limit)
            ->offset($offset)
            ->orderBy('id DESC')
            ->all();
        $link = "?";
        return $this->render('moderated',  compact('ads', 'pages_amount', 'current_page', 'link'));
    }

    public function actionAll(){
        $current_page = \Yii::$app->request->get('page') ? \Yii::$app->request->get('page') : 1;
        $limit = 20;
        $offset = ($current_page - 1) * $limit;
        $rows = Ads::find()->count();
        $pages_amount = ceil($rows/$limit);
        $ads = Ads::find()
            ->limit($limit)
            ->offset($offset)
            ->orderBy('id DESC')
            ->all();
        $link = "?";
        return $this->render('all',  compact('ads', 'pages_amount', 'current_page', 'link'));
    }

    public function actionModerate(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if($request->isPost){
            $id = Yii::$app->request->post('id');
            $ad = Ads::find()->where(['id'=> $id])->one();
            if(!$ad){
                Yii::$app->response->setStatusCode(404);
                return ['message' => 'Not found'];
            }
            if($ad->moderated){
                Yii::$app->response->setStatusCode(404);
                return ['message' => 'Not found'];
            }
            $ad->moderated = 1;
            $ad->save();
            AutopostingTasks::createTasks($ad);
            Yii::$app->response->setStatusCode(200);
            return ['message' => 'OK', 'id'=>$ad->id];
        }else{
            Yii::$app->response->setStatusCode(403);
            return ['message' => 'Forbidden'];
        }
    }

    public function actionInactivate(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if($request->isPost){
            $id = Yii::$app->request->post('id');
            $ad = Ads::find()->where(['id'=> $id])->one();
            if(!$ad){
                Yii::$app->response->setStatusCode(404);
                return ['message' => 'Not found'];
            }
            if(!$ad->active){
                Yii::$app->response->setStatusCode(404);
                return ['message' => 'Not found'];
            }
            $ad->active = 0;
            $ad->save();
            Yii::$app->response->setStatusCode(200);
            return ['message' => 'OK', 'id'=>$ad->id];
        }else{
            Yii::$app->response->setStatusCode(403);
            return ['message' => 'Forbidden'];
        }
    }

    public function actionActivate(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if($request->isPost){
            $id = Yii::$app->request->post('id');
            $ad = Ads::find()->where(['id'=> $id])->one();
            if(!$ad){
                Yii::$app->response->setStatusCode(404);
                return ['message' => 'Not found'];
            }
            if($ad->active){
                Yii::$app->response->setStatusCode(404);
                return ['message' => 'Not found'];
            }
            $ad->active = 1;
            $ad->save();
            Yii::$app->response->setStatusCode(200);
            return ['message' => 'OK', 'id'=>$ad->id];
        }else{
            Yii::$app->response->setStatusCode(403);
            return ['message' => 'Forbidden'];
        }
    }

    public function actionDelete($id){
        $ad = Ads::findOne($id);
        $ad->delete();
        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Успешно удалено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionUpdate($id){
        $ad = Ads::find()
            ->where(['id' => $id])->one();

        $toUrl = Url::toRoute(['save','id' => $id]);

        return $this->render('form',  compact('ad','toUrl'));
    }

    public function actionSave($id) {
        $post = Yii::$app->request->post();
        $ad = Ads::find()->where(['id' => $id])->one();
        if (!$ad){
            return $this->sendJsonData([
                JsonData::SUCCESSMESSAGE => "Обьявление не найдено",
                JsonData::ERROR => 404,
            ]);
        }

        //expiry date
        $expiry_date = date_create(date('Y-m-d h:i:s',$ad->created_at));
        switch($post['Ads']['expiry_date']){
            case Ads::DATE_RANGE_ONE_MONTH :
                date_add($expiry_date, date_interval_create_from_date_string('1 month'));
                break;
            case Ads::DATE_RANGE_THREE_MONTHS :
                date_add($expiry_date, date_interval_create_from_date_string('3 months'));
                break;
            case Ads::DATE_RANGE_SIX_MONTHS :
                date_add($expiry_date, date_interval_create_from_date_string('6 months'));
                break;
            case Ads::DATE_RANGE_ONE_YEAR :
                date_add($expiry_date, date_interval_create_from_date_string('1 year'));
                break;
            case Ads::DATE_RANGE_TWO_YEARS :
                date_add($expiry_date, date_interval_create_from_date_string('2 years'));
                break;
            case Ads::DATE_RANGE_THREE_YEARS :
                date_add($expiry_date, date_interval_create_from_date_string('3 years'));
                break;
            case Ads::DATE_RANGE_UNLIMITED :
                date_add($expiry_date, date_interval_create_from_date_string('20 years'));
                break;
            default :
                date_add($expiry_date, date_interval_create_from_date_string('1 month'));
                break;

        }
        $expiry_date = strtotime(date_format($expiry_date, 'Y-m-d h:i:s'));

        $ad->expiry_date = $expiry_date;
        $ad->title = $post['Ads']['title'];
        $ad->price = $post['Ads']['price'];
        $ad->text = $post['Ads']['text'];
        $ad->placements_id = $post['Ads']['placements_id'];
        $ad->cities_id = $post['Ads']['cities_id'];
        $ad->categories_id = (isset($post['categories'])) ? $post['categories'][0] : null;
        if (!$ad->save()){
            $errors = [];
            foreach($ad->getErrors() as $key => $value){
                $errors['ads-'.$key] =  $value;
            }
            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $errors,
            ]);
        }
        //esli все ок, то поработаем с категориями, каунтерами и файлами

        foreach($ad->files as $ad_file){
            if(!in_array($ad_file->id, $_POST['files'])){
                $ad_file->deleteFile();
            }
        }

        if(isset($_POST['files'])) {
            Files::linkFilesToModel($_POST['files'], $ad);
        }

        $ac = AdCategory::find()->where(['ads_id'=>$ad->id])->all();
        foreach($ac as $i){
            $i->delete();
        }
        $ca = CategoryAd::find()->where(['ads_id'=>$ad->id])->all();
        foreach($ca as $i){
            $i->delete();
        }

        $categories_ids = $ad->getAllCategoriesIds();
        foreach($categories_ids as $c_id){
            // уменьшаем на 1 каунтеры категорий
            $cat_counter = CounterCategory::find()->where(['categories_id' => $c_id, 'countries_id' => $ad->city->region->country->id])->one();
            if($cat_counter and $cat_counter->ads_amount != 0){
                $cat_counter->ads_amount = $cat_counter->ads_amount - 1;
            }else{
                if(!$cat_counter){
                    $cat_counter = new CounterCategory();
                    $cat_counter->categories_id = $c_id;
                    $cat_counter->countries_id = $ad->city->region->country->id;
                }
                $cat_counter->ads_amount = 0;
            }
            $cat_counter->save();
            // уменьшаем на 1 каунтеры для город+категория
            $city_cat_counter = CounterCityCategory::find()->where(['categories_id' => $c_id, 'cities_id' => $ad->cities_id])->one();
            if($city_cat_counter AND $city_cat_counter->ads_amount != 0){
                $city_cat_counter->ads_amount = $city_cat_counter->ads_amount - 1;
            }else{
                if(!$city_cat_counter) {
                    $city_cat_counter = new CounterCityCategory();
                    $city_cat_counter->cities_id = $ad->cities_id;
                }
                $city_cat_counter->ads_amount = 0;
            }
            $city_cat_counter->save();
            //уменьшаем на 1 каунтеры для городa
            $city = City::find()->where(['id' => $ad->cities_id])->one();
            $city->ads_amount = ($city->ads_amount AND $city->ads_amount != 0) ? $city->ads_amount - 1 : 0;
            $city->save();
        }
        //очистим строку ads.categories_list дабы потом ее перезаписать
        $ad->categories_list = null;
        $ad->save();

        $parents_arr = [];
        foreach($post['categories'] as $k => $cat){
            $category_model = Category::find()->where(['id'=>$cat])->one();
            $parents = $category_model->getAllParents();
            foreach($parents as $parent){
                if(!isset($parents_arr[$parent['id']]) AND $parent['id'] != '') {
                    $parents_arr[$parent['id']] = $parent;
                }
            }
            if($k > 0) {
                $adCategory = new AdCategory();
                $adCategory->ads_id = $ad->id;
                $adCategory->categories_id = $cat;
                $adCategory->save();
            }
        }
        $id_str = '';
        foreach($parents_arr as $id => $parent){
            $category_ad = new CategoryAd();
            $category_ad->categories_id = $id;
            $category_ad->ads_id = $ad->id;
            $category_ad->save();
            // counter для категории
            $cat_counter = CounterCategory::find()->where(['categories_id' => $id, 'countries_id' => $ad->city->region->country->id])->one();
            if(!$cat_counter){
                $cat_counter = new CounterCategory();
                $cat_counter->categories_id = $id;
                $cat_counter->countries_id = $ad->city->region->country->id;
                $cat_counter->ads_amount = 1;
            }else{
                $cat_counter->ads_amount = $cat_counter->ads_amount + 1;
            }
            $cat_counter->save();
            //counter для катагории + город
            $city_cat_counter = CounterCityCategory::find()->where(['categories_id' => $id, 'cities_id' => $ad->cities_id])->one();
            if(!$city_cat_counter){
                $city_cat_counter = new CounterCityCategory();
                $city_cat_counter->cities_id = $ad->cities_id;
                $city_cat_counter->categories_id = $id;
                $city_cat_counter->ads_amount = 1;
            }else{
                $city_cat_counter->ads_amount = $city_cat_counter->ads_amount + 1;
            }
            $city_cat_counter->save();
            $id_str .= "|$id|";
        }

        $ad->categories_list = $id_str;
        $ad->save();
        //каунтер для городов
        $city = City::find()->where(['id' => $ad->cities_id])->one();
        $city->ads_amount = $city->ads_amount ? $city->ads_amount + 1 : 1;
        $city->save();

        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Успешно сохранено",
            JsonData::REFRESHPAGE => '',
        ]);
    }
}