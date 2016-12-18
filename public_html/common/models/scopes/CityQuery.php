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

        $this->joinWith(['region' => function(\yii\db\ActiveQuery $query){
            $query->andWhere(['regions.countries_id' => \Yii::$app->user->country->id]);
        }]);
        
        return $this;
    }
    
    /**
     * Параметр поиска
     * @param string $text  Строка поиска
     */
    public function search($text){
        $this->joinWith([
                'cityText', 
                'region.regionText'
        ]);
        
        $this->andWhere(['LIKE','cities_text.name',$text])->orWhere(['LIKE','regions_text.name',$text]);
        
        
        return $this;
    }

}