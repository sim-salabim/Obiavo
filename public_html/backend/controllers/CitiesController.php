<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\City;
use common\models\CityText;
use common\helpers\JsonData;
use yii\helpers\Url;

/**
 * Site controller
 */
class CitiesController extends BaseController
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

    public function actionIndex($region_id = null){
        if ($region_id){
            $region = \common\models\Region::findOne($region_id);
            $cities = City::find()
                        ->withText()
                        ->where(['regions_id' => $region->id])
                        ->all();
        } else {
            $cities = City::find()->withText('cityText')->all();
        }

        return $this->render('index',  compact('region','cities'));
    }

    public function actionAppend($region_id){
        $city = new City;
        $cityText = new CityText;

        $toUrl = Url::toRoute(['save','region_id' => $region_id]);

        return $this->render('form',  compact('city','cityText','toUrl'));
    }

    public function actionEdit($id){
        $city = City::find()
                    ->where(['id' => $id])
                    ->with('cityText')->one();
        $cityText = $city->cityText;

        $toUrl = Url::toRoute(['save','id' => $id]);

        return $this->render('form',  compact('city','cityText','toUrl'));
    }

    public function actionSave($id = null, $region_id = null){
        $post = Yii::$app->request->post();

        if ($id){
            $city = City::findOne($id);
        } else {
            $city = new City();
            $city->regions_id = $region_id;
        }

        $city->loadWithRelation(['cityText'],$post);
        $city->save();

        return $this->sendJsonData([
                JsonData::SUCCESSMESSAGE => "Город \"{$city->cityText->name}\" успешно сохранено",
                JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $city = City::findOne($id);
        $text = $city->cityText;

        $city->delete();

        return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "Город \"{$text->name}\" успешно удален",
                    JsonData::REFRESHPAGE => '',
        ]);
    }
}