<?php

namespace common\models;
use ReflectionClass;

/**
 * This is the model class for table "countries".
 *
 * @property integer $id
 * @property string $name
 * @property string $code_en
 * @property string $code_ru
 * @property integer $placement
 * @property boolean $active
 */
class Advertising extends \yii\db\ActiveRecord
{

    const PLACEMENT_MAIN_PAGE_ABOVE_CITIES_BLOCK = 1;
    const PLACEMENT_MAIN_PAGE_BELOW_CITIES_BLOCK = 2;
    const PLACEMENT_MAIN_PAGE_ABOVE_CATEGORIES_BLOCK = 3;
    const PLACEMENT_MAIN_PAGE_BELOW_CATEGORIES_BLOCK = 4;
    const PLACEMENT_MAIN_PAGE_ABOVE_SEOTEXT_1_BLOCK = 5;
    const PLACEMENT_MAIN_PAGE_BELOW_SEOTEXT_1_BLOCK = 6;
    const PLACEMENT_MAIN_PAGE_ABOVE_SORTING_BLOCK = 7;
    const PLACEMENT_MAIN_PAGE_BELOW_SORTING_BLOCK = 8;
    const PLACEMENT_MAIN_PAGE_ABOVE_ADS_BLOCK = 9;
    const PLACEMENT_MAIN_PAGE_MIDDLE_ADS_BLOCK = 10;
    const PLACEMENT_MAIN_PAGE_BELOW_ADS_BLOCK = 11;
    const PLACEMENT_MAIN_PAGE_ABOVE_SEOTEXT_2_BLOCK = 12;
    const PLACEMENT_MAIN_PAGE_BELOW_SEOTEXT_2_BLOCK = 13;
    const PLACEMENT_CATEGORIES_PAGE_ABOVE_CATEGORIES_BLOCK = 14;
    const PLACEMENT_CATEGORIES_PAGE_BELOW_CATEGORIES_BLOCK = 16;
    const PLACEMENT_CATEGORIES_PAGE_BELOW_SORTING_BLOCK = 17;
    const PLACEMENT_CATEGORIES_PAGE_ABOVE_SORTING_BLOCK = 18;
    const PLACEMENT_CATEGORIES_PAGE_ABOVE_ADS_BLOCK = 19;
    const PLACEMENT_CATEGORIES_PAGE_MIDDLE_ADS_BLOCK = 20;
    const PLACEMENT_CATEGORIES_PAGE_BELOW_ADS_BLOCK = 21;
    const PLACEMENT_CATEGORIES_PAGE_ABOVE_SEOTEXT_BLOCK = 22;
    const PLACEMENT_CATEGORIES_PAGE_BELOW_SEOTEXT_BLOCK = 23;
    const PLACEMENT_CATEGORIES_PAGE_ABOVE_SEOTEXT_2_BLOCK = 30;
    const PLACEMENT_CATEGORIES_PAGE_BELOW_SEOTEXT_2_BLOCK = 31;
    const PLACEMENT_AD_PAGE_ABOVE_CRUMBS_BLOCK = 24;
    const PLACEMENT_AD_PAGE_BELOW_CRUMBS_BLOCK = 25;
    const PLACEMENT_AD_PAGE_ABOVE_CONTACTS_BLOCK = 26;
    const PLACEMENT_AD_PAGE_BELOW_CONTACTS_BLOCK = 27;
    const PLACEMENT_AD_PAGE_ABOVE_TEXT_BLOCK = 28;
    const PLACEMENT_AD_PAGE_BELOW_TEXT_BLOCK = 29;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'advertising';
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code_en' => 'Code EN',
            'active' => 'Active',
            'code_ru' => 'Code RU',
            'ploacement' => 'Place',
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','placement', 'code_en', 'code_ru'], 'required'],
            [['placement','active'], 'integer'],
            [['code_ru','code_en'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public static function getAllPlacementForSelect(){
        try {
            $selfClass = new ReflectionClass(__CLASS__);
        }catch(\ReflectionException $e){
            throw new \ReflectionException($e->getMessage());
        }

        $arr = [];$n = 0;
        foreach($selfClass->getConstants() as $k => $val){
            if(strpos($k, 'PLACEMENT') !== false){
                $arr[$n]['id'] = $val;
                $arr[$n]['name'] = $k;
                $n++;
            }
        }
        return $arr;
    }

    /**
     * @param $placement , advertising->placemnet
     * @return bool|mixed
     */
    public static function getCodeByPlacement($placement){
        $advertising = self::find()->where(['placement' => $placement, 'active' => 1])->one();
        if($advertising){
            if(\frontend\components\Location::getDefaultLanguageId() == \common\models\Language::LANG_EN and $advertising->code_en){
                return $advertising->code_en;
            }
            if(\frontend\components\Location::getDefaultLanguageId() == \common\models\Language::LANG_RU and $advertising->code_ru){
                return $advertising->code_ru;
            }
        }
        return false;
    }
}
