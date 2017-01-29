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

/**
 * Site controller
 */
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
            $regions = $country->getRegions()->with('regionText')->all();

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
        $regionText = new RegionText;

        $toUrl = Url::toRoute(['save','country_id' => $country_id]);

        return $this->render('form',  compact('region','regionText','toUrl'));
    }

    public function actionUpdate($id){
        $region = Region::find()
                    ->where(['id' => $id])
                    ->with('regionText')->one();
        $regionText = $region->regionText;

        $toUrl = Url::toRoute(['save','id' => $region->id]);

        return $this->render('form',  compact('region','regionText','toUrl'));
    }

    public function actionSave($id = null, $country_id = null){
        $post = Yii::$app->request->post();

        if ($id){
            $region = Region::findOne($id);
        } else {
            $region = new Region();
            $region->countries_id = $country_id;
        }

        $region->loadWithRelation(['regionText'],$post);
        $region->save();

        return $this->sendJsonData([
                JsonData::SUCCESSMESSAGE => "\"{$region->regionText->name}\" успешно сохранено",
                JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $country = Region::findOne($id);
        $text = $country->regionText;

        $country->delete();

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

        $text = $region->regionText ? $region->regionText : new RegionText;

        if ($this->isJson()){
            $text->regions_id = $region->id;
            $text->languages_id = $languages_id;
            $text->load(Yii::$app->request->post());
            $text->save();

            return $this->sendJsonData([
                JsonData::SUCCESSMESSAGE => "\"{$text->name}\" успешно сохранено",
                JsonData::REFRESHPAGE => '',
            ]);
        }

        return $this->render('savelang',[
            'regionText' => $text
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
}