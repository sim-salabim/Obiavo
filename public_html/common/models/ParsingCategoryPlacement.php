<?php

namespace common\models;

use common\models\scopes\CategoryPlacementQuery;
use common\models\scopes\PlacementCategoryQuery;
use Yii;

/**
 * This is the model class for table "categories_has_placements".
 *
 * @property integer $id
 * @property integer $categories_id
 * @property integer $placements_id
 *
 * @property Placement $placement
 */
class ParsingCategoryPlacement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parsing_categories_has_placements';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categories_id', 'placements_id'], 'required'],
            [['categories_id', 'placements_id'], 'integer'],

        ];
    }

}
