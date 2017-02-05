<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;

class CountryQuery extends ActiveQuery {

    /**
     * Выбираем страну(ы) по текущему(дефолтному) языку
     */
    public function byLanguage(){

        return $this->andWhere(['languages_id' => \Yii::$app->user->language->id]);
    }

    public function withText($languages_id = null){
        return $this->with(['countryText' => function($query) use ($languages_id){
            $tableName = \common\models\CountryText::tableName();

            if ($languages_id){
                return $query->andWhere(["$tableName.languages_id" => $languages_id]);
            }
        }]);
    }

}