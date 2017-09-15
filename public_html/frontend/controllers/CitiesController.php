<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
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
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if(isset($post['q'])) {
            $query = new Query();
            $query->select([
                'cities.id as id',
                'cities_text.name as text'
            ])->from('cities')
                ->where(
                        ['like', 'cities_text.name', $post['q']])
                ->join('LEFT OUTER JOIN',
                    'cities_text',
                    'cities_text.cities_id = cities.id'
                );
            $command = $query->createCommand();
            $data = $command->queryAll();
        }
        if(isset($data) and $data){
            foreach($data as $row){
                $out[$row['text']] = array('id' => $row['id'], 'text' => $row['text']);
            }
        }
        return $out;
    }
}
