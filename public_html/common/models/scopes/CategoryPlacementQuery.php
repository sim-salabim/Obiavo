<?php
namespace common\models\scopes;

use common\models\CategoryPlacementText;
use yii\db\ActiveQuery;

class CategoryPlacementQuery extends ActiveQuery {

    use \common\models\scopes\traits\Text;

    const TEXT_RELATION = 'categoriesPlacementText';
    const TEXT_RELATION_TABLE = 'categories_placement_text';

    public function withText($languages_id = null){

        return $this->with(['categoriesPlacementText' => function($query) use ($languages_id){
            $tableName = CategoryPlacementText::tableName();

            if ($languages_id){
                return $query->andWhere(["$tableName.languages_id" => $languages_id]);
            }
        }]);
    }
}