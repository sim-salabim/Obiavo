<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\scopes\CategoryQuery;

/**
 * This is the model class for table "parsing_categories".
 *
 * @property string $COL1
 * @property string $COL2
 * @property string $COL3
 * @property string $COL4
 * @property string $COL5
 * @property string $COL6
 * @property string $COL7
 * @property string $COL8
 * @property string $COL9
 *
 *
 */
class ParsingSeoRaw extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'parsing_seo_raw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'COL1',
                'COL2',
                'COL3',
                'COL4',
                'COL5',
                'COL6',
                'COL7',
                'COL8',
                'COL9',
            ],
                'string'],
        ];
    }


    public function transactions() {
        return [
            // scenario name => operation (insert, update or delete)
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }
}
