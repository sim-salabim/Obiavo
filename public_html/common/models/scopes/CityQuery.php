<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;

class CityQuery extends ActiveQuery {

    public function withText(){
        return $this->with('cityText');
    }


    /**
     * Города в текущей локации
     */
    public function byLocation(){

        return $this->joinWith('region')
                ->andWhere(['regions.countries_id' => \Yii::$app->user->country->id]);
    }

}