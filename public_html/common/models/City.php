<?php

namespace common\models;

use Yii;
use common\models\scopes\CityQuery;

/**
 * This is the model class for table "cities".
 *
 * @property integer $id
 * @property integer $regions_id
 * @property string $domain
 * @property integer $active
 * @property integer $sitemap
 * @property string $meta_google
 * @property string $meta_yandex
 * @property string $longitude
 * @property string $latitude
 * @property integer $ads_amount
 *
 * @property Ads[] $ads
 * @property Region $region
 * @property CityText[] $cityTexts
 * @property SocialNetworksGroups[] $socialNetworksGroups
 */
class City extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['regions_id', 'domain'], 'required'],
            [['regions_id', 'active', 'ads_amount'], 'integer'],
            [['active', 'sitemap'], 'integer', 'max' => 1],
            [['domain', 'meta_google', 'meta_yandex'], 'string', 'max' => 255],
            [['longitude', 'latitude'], 'string', 'max' => 100],
            [['regions_id'], 'exist', 'skipOnError' => true, 'targetClass' => Region::className(), 'targetAttribute' => ['regions_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'regions_id' => 'Regions ID',
            'domain' => 'Domain',
            'active' => 'Active',
            'meta_google' => 'Meta Google',
            'meta_yandex' => 'Meta Yandex',
            'longitude' => 'Долгота',
            'latitude' => 'Широта',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => \backend\behaviors\SaveRelation::className(),
                'relations' => ['cityText']
            ],
            [
                'class' => \frontend\behaviors\Multilanguage::className(),
                'relationName' => 'cityText',
                'relationClassName' => CityText::className(),
            ],
        ];
    }

    /**
     * Виртуальный поля для набора объектов, полученный через asArray()
     */
//    public static function virtFields(){
//        return [
//            // Текущий url учитывая город
//            'url'=> function($model) {
//                return self::getUrl($model['domain']);
//            }
//        ];
//    }

    public static function find(){
        return new CityQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAds()
    {
        return $this->hasMany(Ads::className(), ['cities_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialNetworkGroups()
    {
        return $this->hasMany(SocialNetworksGroups::className(), ['cities_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regions_id'])
                    ->with('regionText');
    }

    public function getCityText()
    {
        return $this->hasOne(CityText::className(), ['cities_id' => 'id'])->andWhere(['languages_id' => Language::getId()]);
    }

    public function getCityTexts()
    {
        return $this->hasMany(CityText::className(), ['cities_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['cities_id' => 'id']);
    }

    public function getHref($route){
        return \yii\helpers\Url::toRoute($route);
    }

    /**
     * Вернуть массив данных городов для react компонента
     */
    public static function getComponentData($cities,$url){
        $normalize = function ($city) use ($url){
            return [
                    'id'   => $city->id,
                    'name' => $city->_text->name,
                    'url'  => \yii\helpers\Url::toRoute([
                        'city/generate-url',
                        'cityDomain' => $city['domain'],
                        'url' => $url]),
                    'region' => [
                        'id'   => $city->region->id,
                        'name' => $city->region->_text->name
                    ]
                ];
        };
        $data = [];

        if (!is_array($cities)){
            return $normalize($cities);
        }

        foreach ($cities as $city){
            $data = array_merge([$normalize($city)],$data);
        }

        return $data;
    }

    public static function setCookieLocation($domain = null){
        if(!$domain){
            $_COOKIE["city"] = null;
            $_COOKIE["region"] = null;
            $_COOKIE["country"] = null;
        }else{
            $location = Region::find()->where(['domain' => $domain])->withText()->one();
            $location_domains = [
                'country' => null,
                'region'  => null,
                'city'    => null,
            ];
            if (!$location) {
                $location = City::find()->where(['domain' => $domain])->withText()->one();
                if (!$location) {
                    throw new HttpException(404, 'Not Found');
                }
                $location_domains['city'] = $domain;
                $location_domains['region'] = $location->region->domain;
                $location_domains['country'] = $location->region->country->domain;
                Yii::$app->location->city = $location;
                Yii::$app->location->region = $location->region;
                Yii::$app->location->country = $location->region->country;
            }else{
                $location_domains['city'] = null;
                $location_domains['region'] = $location->domain;
                $location_domains['country'] = $location->country->domain;
                Yii::$app->location->region = $location;
                Yii::$app->location->country = $location->country;
            }
            $_COOKIE["city"] = $location_domains['city'];
            $_COOKIE["region"] = $location_domains['region'];
            $_COOKIE["country"] = $location_domains['country'];
        }
        return $domain;
    }
}
