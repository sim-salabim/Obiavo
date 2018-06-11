<?php

namespace common\models;

/**
 * This is the model class for table "categories_text_raw".
 *
 * @property integer $id
 * @property integer $categories_id
 * @property string $name
 * @property string $name_ip
 * @property string $name_rp
 * @property string $name_dp
 * @property string $name_vp
 * @property string $name_tp
 * @property string $name_pp
 * @property string $name_pp_about
 * @property string $name_p_ip
 * @property string $name_p_rp
 * @property string $name_p_dp
 * @property string $name_p_vp
 * @property string $name_p_tp
 * @property string $name_p_pp
 * @property string $name_p_pp_about
 * @property string $name_where
 * @property string $name_to
 * @property string $name_from
 *
 *
 */
class CategoriesRaw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories_text_raw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_ip', 'name_rp', 'name_dp', 'name_vp', 'name_tp', 'name_pp', 'name_pp_about','name_p_ip', 'name_p_rp', 'name_p_dp', 'name_p_vp', 'name_p_tp', 'name_p_pp'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            "name"
        ];
    }

    public function transactions() {
        return [
            // scenario name => operation (insert, update or delete)
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }

}
