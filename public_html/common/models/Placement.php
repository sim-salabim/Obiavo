<?php

namespace common\models;

use Yii;
use common\models\scopes\PlacementQuery;

/**
 * This is the model class for table "placements".
 *
 * @property integer $id
 *
 * @property CategoriesHasPlacements[] $categoriesHasPlacements
 * @property PlacementsText[] $placementsTexts
 */
class Placement extends \yii\db\ActiveRecord
{
    /*
     * На каком значении PLFCEMENT мы находимся в данный момент
     * в основном устанавливается при разборе url (parserequest)
     */
    private static $_current = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'placements';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }

    public function behaviors()
    {
            return [
                [
                    'class' => \backend\behaviors\SaveRelation::className(),
                    'relations' => ['placementsText']
                ],
                [
                    'class' => \frontend\behaviors\Multilanguage::className(),
                    'relationName' => 'placementsText',
                    'relationClassName' => PlacementsText::className(),
                ],
            ];
    }

    public static function find(){
        return new PlacementQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryPlacement()
    {
        return $this->hasMany(CategoryPlacement::className(), ['placements_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementsTexts()
    {
        return $this->hasMany(PlacementsText::className(), ['placements_id' => 'id']);
    }

    public function getPlacementsText()
    {
        $table = PlacementsText::tableName();
        return $this->hasOne(PlacementsText::className(), ['placements_id' => 'id'])
                    ->andWhere(["`$table`.`languages_id`" => Yii::$app->user->language->id]);
    }

    public static function getCurrent(){
        if (self::$_current) {
            return self::$_current;
        }

        $placementName = Yii::$app->request->get('placement');

        if (empty($placementName)) return null;

        $placement = self::find()
                        ->seoUrl($placementName)
                        ->one();

        self::$_current = $placement;

        return self::$_current;
    }

    public static function setCurrent(Placement $placement){
        self::$_current = $placement;
    }
}
