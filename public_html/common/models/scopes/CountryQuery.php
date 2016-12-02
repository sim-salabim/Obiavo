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

}