<?php
namespace frontend\controllers;

use common\models\Ads;
use common\models\Advertising;
use common\models\Language;
use common\models\libraries\AdsSearch;
use frontend\components\Location;
use frontend\models\SettingsForm;
use Yii;
use yii\web\HttpException;

class UsersController extends BaseController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function actionIm(){

        return $this->render('index');
    }

    public function actionSettings(){
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $url = Yii::$app->request->getPathInfo();
        if(
            (Location::getDefaultLanguageId() == Language::LANG_RU and $url != "nastroiki/") or
            (Location::getDefaultLanguageId() == Language::LANG_EN and $url != "settings/")
        ){
            throw new HttpException(404, 'Not Found');
        }

        $model = new SettingsForm();
        if (Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post(),'');
            if(!$model->validate()) {
                $errors = $model->getErrors();
                foreach($errors as $key => $item){
                    \Yii::$app->getSession()->setFlash($key.'_error', $item[0]);
                }
                return $this->redirect('/nastroiki/');
            }else{
                $model->changeSettings();
                \Yii::$app->getSession()->setFlash('message', __('Successfully saved'));
                return $this->redirect('/nastroiki/');
            }
        } else {
            return $this->render('settings');
        }
    }

    public function actionMyAds(){
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(
            (Yii::$app->request->getPathInfo() == "moi-obiavleniya/" and Location::getDefaultLanguageId() != Language::LANG_RU) OR
            (Yii::$app->request->getPathInfo() == "my-ads/" and Location::getDefaultLanguageId() != Language::LANG_EN)
        ){
            throw new HttpException(404, 'Not Found');
        }
        $sort = Yii::$app->request->get('sort');
        $direction = Yii::$app->request->get('direction');
        $this->setPageTitle(__('My ads'));
        $librarySearch = new AdsSearch();
        $librarySearch->setUser(Yii::$app->user->identity);
        $librarySearch->setAll(true);
        $limit = (Yii::$app->request->get('loaded')) ? Yii::$app->request->get('loaded') + $librarySearch->limit : $librarySearch->limit;
        $page = (Yii::$app->request->get('page')) ? Yii::$app->request->get('page') : $librarySearch->page;
        $librarySearch->setPage($page);
        $librarySearch->setLimit($limit);
        $librarySearch->setConsiderLocation(false);
        if($sort AND $direction) {
            $librarySearch->setSorting($sort." ".$direction);
        }
        return $this->render('my-ads', [
            'loaded' => $limit,
            'ads_search' => (new Ads())->getList($librarySearch),
            'library_search' => $librarySearch,
            'advertising_code_above_categories' => Advertising::getCodeByPlacement(Advertising::PLACEMENT_CATEGORIES_PAGE_ABOVE_CATEGORIES_BLOCK),
            'advertising_code_below_categories' => Advertising::getCodeByPlacement(Advertising::PLACEMENT_CATEGORIES_PAGE_BELOW_CATEGORIES_BLOCK),
            'advertising_code_above_sorting_block' => Advertising::getCodeByPlacement(Advertising::PLACEMENT_CATEGORIES_PAGE_ABOVE_SORTING_BLOCK),
            'advertising_code_below_sorting_block' => Advertising::getCodeByPlacement(Advertising::PLACEMENT_CATEGORIES_PAGE_BELOW_CATEGORIES_BLOCK),
            'advertising_code_above_ads_block' => Advertising::getCodeByPlacement(Advertising::PLACEMENT_CATEGORIES_PAGE_ABOVE_ADS_BLOCK),
            'advertising_code_middle_ads_block' => Advertising::getCodeByPlacement(Advertising::PLACEMENT_CATEGORIES_PAGE_MIDDLE_ADS_BLOCK),
            'advertising_code_below_ads_block' => Advertising::getCodeByPlacement(Advertising::PLACEMENT_CATEGORIES_PAGE_BELOW_ADS_BLOCK),
        ]);
    }
}
