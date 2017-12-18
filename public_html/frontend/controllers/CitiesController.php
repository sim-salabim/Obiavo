<?php
namespace frontend\controllers;

use Yii;
use yii\db\Expression;
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
                'cities.regions_id as regions_id',
                'cities_text.name as text',
                'countries.domain as country_domain',
                'cities.domain as domain'
            ])->from('cities')
                ->where("cities_text.name LIKE '".$post['q']."%'")
                ->andWhere(['countries.domain' => Yii::$app->location->country])
                ->andWhere(['cities_text.languages_id' => Yii::$app->location->country->languages_id])
                ->join('LEFT OUTER JOIN',
                    'cities_text',
                    'cities_text.cities_id = cities.id'
                )
                ->join('LEFT OUTER JOIN',
                    'regions',
                    'regions.id = cities.regions_id'
                    )
                ->join('LEFT OUTER JOIN',
                    'countries',
                    'countries.id = regions.countries_id'
                );
            $command = $query->createCommand();
            $data = $command->queryAll();
        }
        if(isset($data) and $data){
            foreach($data as $row){
                $out[] = array('id' => $row['id'], 'text' => $row['text'], 'domain' => $row['domain']);
            }
        }
        return $out;
    }
}
