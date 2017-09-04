<?php
namespace frontend\controllers;

use frontend\components\Location;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use common\models\City;
use yii\db\Query as Query;


class CitiesController extends Controller
{

    public $enableCsrfValidation = false;
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


    public function actionSearchCitiesForSelect(){
        $post = Yii::$app->request->post();
        $q = $post['q'];
        $id = null;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];

        $query = new Query();
        $query->select([
            'cities.id as id',
            'cities_text.name as text'
        ])->from('cities')
            ->where(['like', 'text', $q])
            ->where(['=', 'cities_text.languages_id', 1])
            ->join(	'LEFT OUTER JOIN',
                'cities_text',
                'cities_text.cities_id = cities.id'
            );
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);

        return $out;
    }
}
