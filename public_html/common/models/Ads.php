<?php

namespace common\models;

use Yii;
use frontend\helpers\TransliterationHelper;

/**
 * This is the model class for table "ads".
 *
 * @property integer $id
 * @property integer $cities_id
 * @property integer $users_id
 * @property integer $categories_id
 * @property string $title
 * @property string $text
 * @property int $price
 * @property int $prlacements_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $expiry_date
 *
* @property City $city
* @property User $user
* @property Category $category
* @property Placement $placement
 * @property Files[] $files
 *
 */
class Ads extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ads';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cities_id', 'users_id', 'categories_id'], 'required'],
            [['title', ], 'string', 'max' => 100],
            [['text', ], 'string', 'max' => 1000],
            [['cities_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['cities_id' => 'id']],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['users_id' => 'id']],
            [['categories_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['categories_id' => 'id']],
            [['created_at'], 'default','value' => time()],
            [['updated_at'],'default', 'value' => time()]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cities_id' => 'City',
            'users_id' => 'User',
            'categories_id' => 'Category',
            'title' => 'Title',
            'text' => 'Text',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cities_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles(){
        return $this->hasMany(Files::className(), ['id' => 'files_id'])
            ->viaTable('ads_has_files', ['ads_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacement()
    {
        return $this->hasOne(Placement::className(), ['id' => 'placements_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'users_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'categories_id']);
    }

    /** Генерирует уникальный для объявлений урл
     *
     * @param $str, строка на русском языке
     * @param int $idx
     * @return mixed|string
     */
    public function generateUniqueUrl($str, $idx = 0){
        $str = ($idx == 0) ? $str : $str."-".$idx;
        $url = TransliterationHelper::transliterate($str);
        $found = Ads::findOne(['url' => $url]);
        if($found){
            return $this->generateUniqueUrl($str, ++$idx);
        }else{
            return $url;
        }
    }
}
