<?php

namespace common\models;

use common\models\scopes\AddApplicationQuery;

/**
 * This is the model class for table "add_application".
 *
 * @property integer $id
 * @property integer $active
 */
class AddApplication extends \yii\db\ActiveRecord
{
    const SCENARIO_DEFAULT = 'default';
    const STATUS_ACTIVE = 1;
    const STATUS_UNACTIVE = 0;

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'active',
            ],
        ];
    }

    public static function find(){
        return new AddApplicationQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            [
                'class' => \backend\behaviors\SaveRelation::className(),
                'relations' => ['addApplicationText']
            ],
            [
                'class' => \frontend\behaviors\Multilanguage::className(),
                'relationName' => 'addApplicationText',
                'relationClassName' => AddApplicationText::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'add_application';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['active','default','value' => self::STATUS_ACTIVE]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'active' => 'Active',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddApplicationText()
    {
        return $this->hasOne(AddApplicationText::className(), ['add_application_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddApplicationTexts()
    {
        return $this->hasMany(AddApplicationText::className(), ['add_application_id' => 'id']);
    }

}
