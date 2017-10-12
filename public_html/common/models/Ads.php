<?php

namespace common\models;

use common\models\libraries\AdsSearch;
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
    const PRICE_LABEL = 'руб';
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
     * @return string
     */
    public function avatar($thumbnail = true){
        $thumbnail_str = ($thumbnail) ? Files::THUMBNAIL : '';
        if(isset($this->files[0])){
            if(file_exists(Yii::$app->params['uploadPath']."/".$this->files[0]->hash.".".$this->files[0]->ext->ext)){

                return "/files/".$this->files[0]->hash.$thumbnail_str.".".$this->files[0]->ext->ext;
            }
        }
        return "/files/placeholder".$thumbnail_str.".png";
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

    public function getList(AdsSearch $model){
        $where_conditions = [];
        $like_conditions = [];
        $category_conditions = [];
        $location_conditions = [];
        $expired_conditions = [];
        if($model->user) $where_conditions['users_id'] = $model->user;
        if($model->category) {
            $cat_ids_arr[] = "$model->category";
            $cats = (new \yii\db\Query())
                ->select(['id'])
                ->from('categories')
                ->groupBy(['id'])
                ->where(['parent_id' => $model->category])
                ->all();
            if(!empty($cats)){
                foreach($cats as $cat){
                    array_push($cat_ids_arr, $cat['id']);
                }
            }
            $category_conditions = [
                'in', 'categories_id', $cat_ids_arr
            ];
        }
        if($model->action) $where_conditions['placements_id'] = $model->action;
        if($model->expired){
            $expired_conditions = [
                '>' , 'expity_date', time()
            ];
        }else{
            $expired_conditions = [
                '<' , 'expiry_date', time()
            ];
        }
        if($model->query) $like_conditions = [
            'like', 'title' , "$model->query"
        ];
        $cities_id_arr = [];
        if($model->location['city']){
            $cities_id_arr = [$model->location['city']];
        }else{
            if($model->location['region']){
                $cities_ids = (new \yii\db\Query())
                    ->select(['id'])
                    ->from('cities')
                    ->groupBy(['id'])
                    ->where(['regions_id' => $model->location['region']])
                    ->all();
                if(!empty($cities_ids)){
                    foreach($cities_ids as $id){
                        array_push($cities_id_arr, $id['id']);
                    }
                }
            }else{
                $cities_ids = (new \yii\db\Query())
                    ->select(['id'])
                    ->from('cities')
                    ->groupBy(['id'])
                    ->where(['regions_id' => 'SELECT id FROM regions WHERE countries_id = '.$model->location['country']])
                    ->all();
                if(!empty($cities_ids)){
                    foreach($cities_ids as $id){
                        array_push($cities_id_arr, $id['id']);
                    }
                }
            }
        }
        if(!empty($cities_id_arr)){
            $location_conditions = ['in', 'cities_id', $cities_id_arr];
        }else if(
            empty($cities_id_arr)
            AND
            ($model->location['city'] OR $model->location['region'] OR $model->location['country'])){
            $location_conditions = ['cities_id' => 0];
        }

        $ads = Ads::find()
            ->where($where_conditions)
            ->andFilterWhere($expired_conditions)
            ->andFilterWhere($location_conditions)
            ->andFilterWhere($category_conditions)
            ->andFilterWhere($like_conditions)
            ->orderBy($model->sorting)
            ->limit($model->limit)
            ->orderBy($model->sorting)
            ->all();
        $count = Ads::find()
            ->where($where_conditions)
            ->andFilterWhere($expired_conditions)
            ->andFilterWhere($location_conditions)
            ->andFilterWhere($category_conditions)
            ->andFilterWhere($like_conditions)
            ->orderBy($model->sorting)
            ->limit($model->limit)
            ->orderBy($model->sorting)
            ->count();
        return ['items' => $ads, 'count' => $count];
    }
}
