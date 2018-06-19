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
            [[
                'COL2',
                'COL3',
                'COL4',
                'COL5',
                'COL6',
                'COL7',
                'COL8',
                'COL9',
                'COL10',
                'COL11',
                'COL12',
                'COL13',
                'COL14',
                'COL15',
                'COL16',
                'COL17',
                'COL18',
                'COL19',
                'COL20',
                'COL21',
                'COL22',
            ],
                'string', 'max' => 255],
        ];
    }


    public function transactions() {
        return [
            // scenario name => operation (insert, update or delete)
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }
}
