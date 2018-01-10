<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Region;
use common\models\RegionText;
use common\helpers\JsonData;
use yii\helpers\Url;
use common\models\Country;
use common\models\Language;

class RegionsController extends BaseController
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

    public function actionIndex($country_id = null){

        $country = new Country;
        $breadcrumbs = '';

        if ($country_id){
            $country = Country::findOne($country_id);
            $regions = $country->getRegions()->withText()->all();

            $breadcrumbs = $this->getBreadcrumbs([
                                'breadcrumbs' => [
                                    "Регионы {$country->countryText->name_rp}"
                                ],
                                'homeLink' => ['label' => 'Страны', 'url' => '/countries']
                            ]);
        } else {
            $regions = Region::find()->with('regionText')->all();
        }

        return $this->render('index',  compact('regions','country','breadcrumbs'));
    }

    public function actionAppend($country_id){
        $region = new Region;

        $toUrl = Url::toRoute(['save','country_id' => $country_id]);

        return $this->render('form',  compact('region','toUrl'));
    }

    public function actionUpdate($id){
        $region = Region::find()
                    ->where(['id' => $id])
                    ->withText()
                    ->one();

        $toUrl = Url::toRoute(['save','id' => $region->id]);

        return $this->render('form',  compact('region','toUrl'));
    }

    public function actionSave($id = null, $country_id = null){
        $post = Yii::$app->request->post();

        if ($id){
            $region = Region::findOne($id);
        } else {
            $region = new Region();
            $region->countries_id = $country_id;
        }

        if (!$region->saveWithRelation($post)){

            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $region->getErrors(),
            ]);
        }

        return $this->sendJsonData([
                JsonData::SUCCESSMESSAGE => "\"{$region->regionText->name}\" успешно сохранено",
                JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $region = Region::findOne($id);
        $text = $region->_mttext;

        $region->delete();

        return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "Регион \"{$text->name}\" успешно удален",
                    JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionSaveLang($id,$languages_id){

        $region = Region::find()
                        ->where(['id' => $id])
                        ->withText($languages_id)
                        ->one();

        if ($this->isJson()){
            $text = $region->_mttext;
            $text->regions_id = $region->id;
            $text->languages_id = $languages_id;
            $text->load(Yii::$app->request->post());
            $text->save();

            if ($text->save()){
                return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "\"{$text->name}\" успешно сохранено",
                    JsonData::REFRESHPAGE => '',
                ]);
            }

            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => \yii\widgets\ActiveForm::validate($text),
            ]);
        }

        return $this->render('savelang',[
            'region' => $region
        ]);
    }

    public function getBreadcrumbs($options){
        $homeLink = $options['homeLink'];
        $breadcrumbs = $options['breadcrumbs'];

        return \yii\widgets\Breadcrumbs::widget([
            'homeLink' => $homeLink,
            'links' => isset($breadcrumbs) ? $breadcrumbs : []
        ]);
    }

    public function actionSearch(){
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = $post['query'];
        $country_id = (isset($post['country_id'])) ? $post['country_id'] : 1;
        $regions = Region::find()
            ->leftJoin('regions_text', 'regions_text.regions_id = regions.id')
            ->where("regions_text.name LIKE '".$query."%'")
            ->andWhere('countries_id = '.$country_id)
            ->all();
        $result = [];
        foreach($regions as $region){
            $result[$region->id] = array('id' => $region->id, 'text' => $region->_text->name, 'domain' => $region->domain);
        }
        return $result;
    }
}