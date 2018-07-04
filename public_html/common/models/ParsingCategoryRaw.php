<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\scopes\CategoryQuery;

/**
 * This is the model class for table "parsing_categories".
 *
 * @property integer $COL1
 * @property string $COL2
 * @property string $COL3
 * @property string $COL4
 * @property string $COL5
 * @property string $COL6
 * @property string $COL7
 * @property string $COL8
 * @property string $COL9
 * @property string $COL10
 * @property string $COL11, короткое имя
 * @property string $COL12
 * @property string $COL13
 * @property string $COL14
 * @property string $COL15
 * @property string $COL16
 * @property string $COL17
 * @property string $COL18
 * @property string $COL19
 * @property string $COL20
 * @property string $COL21
 * @property string $COL22
 * @property integer $COL23
 *
 *
 */
class ParsingCategoryRaw extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'parsing_categories_raw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['COL1', 'COL23'], 'integer'],
        ];
    }


    public function transactions() {
        return [
            // scenario name => operation (insert, update or delete)
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }
}
