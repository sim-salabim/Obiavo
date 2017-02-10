<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;

class CountryQuery extends ActiveQuery {

    use \common\models\scopes\traits\Text;

    const TEXT_RELATION = 'countryText';
    const TEXT_RELATION_TABLE = 'countries_text';

    /**
     * Выбираем страну(ы) по текущему(дефолтному) языку
     */
//    public function byLanguage(){
//
//        return $this->andWhere(['languages_id' => \Yii::$app->user->language->id]);
//    }

    public function current(){
        return $this->andWhere(['domain' => \Yii::$app->request->serverName]);
    }

//    public function withText($languages_id = null){
//        return $this->with(['countryText' => function($query) use ($languages_id){
//            $tableName = \common\models\CountryText::tableName();
//
//            if ($languages_id){
//                return $query->andWhere(["$tableName.languages_id" => $languages_id]);
//            }
//        }]);
//    }

}