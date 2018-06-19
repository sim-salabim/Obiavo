<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\scopes\CategoryQuery;

/**
 * This is the model class for table "parsing_categories".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $techname
 * @property string $name
 * @property integer $active
 * @property integer $social_networks_groups_main_id
 * @property boolean $brand
 * @property boolean $clean_harakterisitka
 * @property boolean $href
 * @property integer $excel_id
 * @property integer $seo_id
 * @property integer $href_id
 *
 *
 */
class ParsingCategory extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'parsing_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'active'], 'integer'],
            [['techname'], 'required'],
            [['techname'], 'string', 'max' => 255],
            //[['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }


    public function transactions() {
        return [
            // scenario name => operation (insert, update or delete)
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }
}
