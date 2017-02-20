<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;

class CityQuery extends ActiveQuery {

    use \common\models\scopes\traits\Text;

    const TEXT_RELATION = 'cityText';
    const TEXT_RELATION_TABLE = 'cities_text';

    /**
     * Адаптация вируальных полей
     */
//    public function populate($rows){
//        $models=parent::populate($rows);
//
//        if(!$this->asArray){
//            return $models;
//        }else{
//            $class = $this->modelClass;
//            $dopfields=method_exists($class, 'virtFields')?$class::virtFields():[];
//            foreach ($models as &$model) {
//              if(!empty($dopfields)){
//                  foreach($dopfields as $attr=>$val){
//                      if(is_string($val)){
//                          $model=array_merge($model,[$attr=>$val]);
//                      }elseif(is_callable($val)){
//                          $model=array_merge($model,[$attr=>call_user_func($val, $model)]);
//                      }
//                  }
//              }
//            }
//            return $models;
//        }
//    }

    /**
     * Города в текущей локации
     */
    public function byLocation(){

        $this->joinWith(['region' => function(\yii\db\ActiveQuery $query){
            $query->andWhere(['regions.countries_id' => \Yii::$app->location->country->id])
                  ->withText();
        }]);

        return $this;
    }


    public function whereDomain($domainName){

        $this->andWhere(['cities.domain' => $domainName]);

        return $this;
    }

    /**
     * Параметр поиска
     * @param string $text  Строка поиска
     */
    public function search($text, $operator = 'LIKE'){
        $this->joinWith([
          'cityText ct' => function (\yii\db\ActiveQuery $query) use ($text,$operator) {
              $query->andWhere([$operator,'ct.name',$text])
                    ->onCondition(['ct.languages_id' => \Yii::$app->location->language->id]);
          }]);


        return $this;
    }

    public function searchWithRegion($text, $operator = 'LIKE'){
        $this->joinWith([
                'cityText',
                'region.regionText'
        ]);

        $this->andWhere([$operator,'cities_text.name',$text])->orWhere([$operator,'regions_text.name',$text]);


        return $this;
    }

}