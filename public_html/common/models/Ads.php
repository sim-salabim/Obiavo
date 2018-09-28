<?php

namespace common\models;

use common\models\libraries\AdsSearch;
use frontend\helpers\ArrayHelper;
use frontend\helpers\LocationHelper;
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
 * @property boolean $only_locally
 * @property int $price
 * @property int $prlacements_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $expiry_date
 *
* @property City $city
* @property User $user
* @property Category $category
* @property Category[] $categories
* @property Placement $placement
 * @property Files[] $files
 * @property AdsView[] $views
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
            [['only_locally'], 'integer', 'max' => 1],
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
     * @return string
     */
    public function url(){
        $domain = '';
        if($this->only_locally){
            $domain = $this->city->domain."/";
        }
        return $this->url.'/'.$domain;
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
    public function getViews(){
        return $this->hasMany(AdsView::className(), ['id' => 'users_id']);
    }

    /**
     * @return string
     */
    public function avatar($thumbnail = true){
        $thumbnail_str = ($thumbnail) ? Files::THUMBNAIL : '';
        if(isset($this->files[0])){
            if(file_exists(Yii::$app->params['uploadPath']."/".$this->files[0]->hash)){

                return "/files/".$this->files[0]->hash.$thumbnail_str;
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
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'categories_id'])
            ->viaTable('ads_has_categories', ['ads_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'categories_id'])->with('categoriesText');
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

    /**
     *
     *
     * @param AdsSearch $model
     * @param bool $ads_list, выводить ли список
     * @return array [
     * 'items' - список обьявлений,
     * 'count' - количество обьявлений попавших под выборку(не учитывая параметров пагинации),
     * 'price_range' - массив с мин и макс ценой в выборке ['min', 'max'],
     * 'views_amount' - общее количество просмотров обьявлений попавших под выборку,
     * 'finished_deals' - количество завершенных сделок попавших под выборкку]
     */
    public static function getList(AdsSearch $model, $ads_list = true){
        $where_conditions = [];
        $user_conditions = [];
        $like_conditions = [];
        $category_conditions = [];
        $additional_category_conditions = [];
        $location_conditions = [];
        $expired_conditions = [];
        if($model->user) $user_conditions['users_id'] = $model->user->id;
        if($model->main_category) {
            $category = Category::findOne($model->main_category);
            $kids_categories = Category::getAllChildren([$category]);
            $cat_ids_arr = ArrayHelper::getColumn($kids_categories, 'id');
            array_push($cat_ids_arr, $model->main_category);
            $category_conditions = [
                'in', 'ads.categories_id', $cat_ids_arr
            ];
            $ad_ids = (new \yii\db\Query())
                ->select(['ads_id'])
                ->from('ads_has_categories')
                ->groupBy(['ads_id'])
                ->where(["categories_id" => $cat_ids_arr])
                ->all();
            if($ad_ids){
                $ad_ids_arr = [];
                foreach($ad_ids as $i){
                    $ad_ids_arr[] = $i['ads_id'];
                }
                $additional_category_conditions = ["ads.id" => $ad_ids_arr];
            }
        }
        if($model->action) $where_conditions['placements_id'] = $model->action;
        if(!$model->all) {
            if ($model->expired) {
                $expired_conditions = [
                    '<', 'expiry_date', time()
                ];
            } else {
                $expired_conditions = [
                    '>', 'expiry_date', time()
                ];
            }
        }
        if($model->query) $like_conditions = [
            'like', 'title' , "$model->query"
        ];
        if($model->consider_location) {
            $cities_id_arr = [];
            if ($model->location['city']) {
                $cities_id_arr = [$model->location['city']->id];
            } else {
                if ($model->location['region']) {
                    $cities_ids = (new \yii\db\Query())
                        ->select(['id'])
                        ->from('cities')
                        ->groupBy(['id'])
                        ->where(['regions_id' => $model->location['region']->id])
                        ->all();
                    if (!empty($cities_ids)) {
                        foreach ($cities_ids as $id) {
                            array_push($cities_id_arr, $id['id']);
                        }
                    }
                } else {
                    $cities_ids = (new \yii\db\Query())
                        ->select(['id'])
                        ->from('cities')
                        ->groupBy(['id'])
                        ->where(['in', 'regions_id',
                            (new \yii\db\Query())->select('id')->from('regions')->where(['countries_id' => $model->location['country']->id])])
                        ->all();
                    if (!empty($cities_ids)) {
                        foreach ($cities_ids as $id) {
                            array_push($cities_id_arr, $id['id']);
                        }
                    }
                }
            }
            if (!empty($cities_id_arr)) {
                $location_conditions = ['in', 'cities_id', $cities_id_arr];
            } else if (
                empty($cities_id_arr)
                AND
                ($model->location['city'] OR $model->location['region'] OR $model->location['country'])
            ) {
                $location_conditions = ['cities_id' => 0];
            }
        }
        $ads = [];
        if($ads_list) {
            $ads = Ads::find()
                ->where($where_conditions)
                ->andFilterWhere($expired_conditions)
                ->andFilterWhere($user_conditions)
                ->andFilterWhere($location_conditions)
                ->andFilterWhere($category_conditions)
                ->orFilterWhere($additional_category_conditions)
                ->andFilterWhere($like_conditions)
                ->orderBy($model->sorting)
                ->offset(($model->page - 1)* $model->limit)
                ->limit($model->limit)
                ->all();
        }
        $count = Ads::find()
            ->where($where_conditions)
            ->andFilterWhere($user_conditions)
            ->andFilterWhere($expired_conditions)
            ->andFilterWhere($location_conditions)
            ->andFilterWhere($category_conditions)
            ->orFilterWhere($additional_category_conditions)
            ->andFilterWhere($like_conditions)
            ->orderBy($model->sorting)
            ->count();
        $price_range = (new \yii\db\Query())
            ->select('MAX(price) as max, MIN(price) as min')
            ->from('ads')
            ->where($where_conditions)
            ->andFilterWhere($user_conditions)
            ->andFilterWhere($expired_conditions)
            ->andFilterWhere($location_conditions)
            ->andFilterWhere($category_conditions)
            ->orFilterWhere($additional_category_conditions)
            ->andFilterWhere($like_conditions)
            ->orderBy($model->sorting)
            ->one();
        $views_expired_conditions = ['>', 'expiry_date', time()];
        $views_amount = (new \yii\db\Query())
            ->from('ads_views')
            ->where(['in', 'ads_id',
                (new \yii\db\Query())
                ->select('id')
                ->from('ads')
                ->andFilterWhere($user_conditions)
                ->andFilterWhere($views_expired_conditions)
                ->andFilterWhere($location_conditions)
                ->andFilterWhere($category_conditions)
                ->andFilterWhere($like_conditions)
                ->orderBy($model->sorting)
            ])
            ->count();
        $finished_ads = (new \yii\db\Query())
            ->from('ads')
            ->andFilterWhere(['<', 'expiry_date', time()])
            ->count();
        return ['items' => $ads, 'count' => $count, 'price_range' => $price_range, 'views_amount' => $views_amount, 'finished_deals' => $finished_ads];
    }

    /** Возвращает строку с human date
     * @return string
     */
    public function getHumanDate(){
        $ad_day_number = date('z', $this->created_at);
        $today_number = date('z', time());
        $daystr = '';
        if($ad_day_number == $today_number){
            $daystr .= __('Today');
        }else if($today_number == (1 + $ad_day_number)){
            $daystr .= __('Yesterday');
        }else{
            $daystr .= date('d:m:y', $this->created_at);
        }
        $daystr .= ' '.date('H:i', $this->created_at);
        return $daystr;
    }

    function getBreadcrumbs(){
        $parent = $this->category;
        $breadcrumbs = [];
        $breadcrumbs[] = ['label' => $this->title, 'link' => $this->url];
        while ($parent) {
            $breadcrumbs[] = ['label' => $parent->_text->name, 'link' => '/'.$parent->_text->url."/"];
            $parent = $parent->getParent()->one();
        }
        return array_reverse($breadcrumbs);
    }
}
