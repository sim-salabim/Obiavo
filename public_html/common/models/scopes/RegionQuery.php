<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;

class RegionQuery extends ActiveQuery {

    use \common\models\scopes\traits\Text;

    const TEXT_RELATION = 'regionText';
    const TEXT_RELATION_TABLE = 'regions_text';

    /**
     * Категория с текущим переводом
     */
//    public function withText($languages_id = null){
//
//        return $this->with(['regionText' => function($query) use ($languages_id){
//            $tableName = \common\models\RegionText::tableName();
//
//            if ($languages_id){
//                return $query->andWhere(["$tableName.languages_id" => $languages_id]);
//            }
//        }]);
//    }
}