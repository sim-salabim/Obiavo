<?php
namespace backend\controllers;

use common\helpers\JsonData;
use common\models\City;
use common\models\CityOrder;
use common\models\Country;
use common\models\Region;
use common\models\SocialNetworks;
use common\models\SocialNetworksGroups;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class SnGroupsController extends BaseController
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

    public function actionIndex($sn_group_id = null){
        $sn_group = null;

        if ($sn_group_id){
            $sn_group = SocialNetworksGroups::findOne($sn_group_id);
        } else {
            $sn_groups = SocialNetworksGroups::find()->all();
        }

        return $this->render('index',  compact('sn_group', 'sn_groups'));
    }

    public function actionCreate(){
        $sn_group = new SocialNetworksGroups();

        $toUrl = Url::toRoute(['save']);

        return $this->render('form',  compact('sn_group','toUrl'));
    }

    public function actionUpdate($id){
        $sn_group = SocialNetworksGroups::find()
            ->where(['id' => $id])->one();

        $toUrl = Url::toRoute(['save','id' => $id]);

        return $this->render('form',  compact('sn_group','toUrl'));
    }

    public function actionSave($id = null){
        $post = Yii::$app->request->post();

        if ($id){
            $sn_group = SocialNetworksGroups::findOne($id);
        } else {
            $sn_group = new SocialNetworksGroups();
        }
        if($post['SocialNetworksGroups']['cities_id']){
            $city = City::findOne($post['SocialNetworksGroups']['cities_id']);
            $post['SocialNetworksGroups']['regions_id'] = $city->region->id;
            $post['SocialNetworksGroups']['countries_id'] = $city->region->country->id;
        }else{
            if($post['SocialNetworksGroups']['regions_id']) {
                $region = Region::findOne($post['SocialNetworksGroups']['regions_id']);
                $post['SocialNetworksGroups']['countries_id'] = $region->country->id;
            }
        }
        $sn_group->load($post);
        if (!$sn_group->save()){
            $errors = [];
            foreach ($sn_group->getErrors() as $key => $error){
                $errors['socialnetworksgroups-'.$key] = $error;
            }
            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $errors,
            ]);
        }
        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Успешно сохранено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){
        $sn_group = SocialNetworks::findOne($id);
        $sn_group->delete();
        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Успешно удалено",
            JsonData::REFRESHPAGE => '',
        ]);
    }
    /**
     * @return array
     */
    public function actionSearch($sn_id = null){
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = $post['query'];
        $andWhere = [];
        if($sn_id){
            $andWhere = ['social_networks_id' => $sn_id];
        }
        $groups = SocialNetworksGroups::find()
            ->where("name LIKE '".$query."%'")
            ->andWhere($andWhere)
            ->all();
        $result = [];
        foreach($groups as $group){
            $result[$group->id] = array('id' => $group->id, 'text' => $group->name);
        }
        return $result;
    }
}