<?php

namespace common\models;

use common\models\libraries\AdsSearch;
use frontend\helpers\TransliterationHelper;
use Yii;

/**
 * This is the model class for table "ads".
 *
 * @property integer $id
 * @property integer $cities_id
 * @property integer $users_id
 * @property integer $categories_id
 * @property string $title
 * @property string $text
 * @property string $session_token
 * @property boolean $only_locally
 * @property boolean $active
 * @property int $price
 * @property int $placements_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $expiry_date
 * @property int $ads_amount
 *
* @property City $city
* @property User $user
* @property Category $category
* @property Category[] $categories
* @property Category[] $availableCategories
* @property Placement $placement
 * @property Files[] $files
 * @property AdsView[] $views
 *
 */
class Ads extends \yii\db\ActiveRecord
{
    const PRICE_LABEL = 'руб';
    const DATE_TYPE_CREATION = 'created_at';
    const DATE_TYPE_UPDATING = 'updating_at';
    const DATE_TYPE_EXPIRATION = 'expiry_date';
    const DATE_RANGE_ONE_MONTH = 'one_month';
    const DATE_RANGE_THREE_MONTHS= 'three_months';
    const DATE_RANGE_SIX_MONTHS = 'six_months';
    const DATE_RANGE_ONE_YEAR = 'one_year';
    const DATE_RANGE_TWO_YEARS = 'two_years';
    const DATE_RANGE_THREE_YEARS = 'three_years';
    const DATE_RANGE_UNLIMITED = 'unlimited';
    const DEFAULT_LINK = 'podat-obiavlenie';
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
            [['title' ], 'string', 'max' => 100],
            [['text' ], 'string', 'max' => 1000],
            [['session_token' ], 'string'],
            [['only_locally', 'active'], 'integer', 'max' => 1],
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
            'title' => 'Заголовок',
            'text' => 'Описание',
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
     * @return \yii\db\ActiveQuery
     */
    public function getAvailableCategories()
    {
        return $this->hasOne(Category::className(), ['id' => 'categories_id'])
            ->viaTable('categories_has_ads', ['ads_id' => 'id']);
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
        $active_conditions = [];
        if($model->user) $user_conditions  = ["=", "ads.users_id", $model->user->id];
        if($model->action){
            $where_conditions = ['=', 'ads.placements_id', $model->action];
        }
        if($model->active != null){
            if($model->active == false){
                $active_conditions = ['=', 'active', 0];
            }else{
                $active_conditions = ['=', 'active', 1];
            }
        }
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
        }else{
            $expired_conditions = [
                '<>', 'expiry_date', time()
            ];
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
                    //TODO consider caching
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
        $add_expired_conditions = [];
        if(!empty($expired_conditions)){
            $add_expired_conditions = [$expired_conditions[0], "ads.".$expired_conditions[1],$expired_conditions[2]];
        }
        $add_active_conditions = [];
        if(!empty($active_conditions)){
            $add_active_conditions = [$active_conditions[0], "ads.".$active_conditions[1], $active_conditions[2]];
        }
        $add_user_conditions = [];
        if(!empty($user_conditions)){
            $add_user_conditions = [$user_conditions[0], "ads.".$user_conditions[1], $user_conditions[2]];
        }
        $add_location_conditions = [];
        if(!empty($location_conditions)){
            $add_location_conditions = [$location_conditions[0], "ads.".$location_conditions[1], $location_conditions[2]];
        }
        $add_like_conditions = [];
        if(!empty($like_conditions)){
            $add_like_conditions = [$like_conditions[0], "ads.".$like_conditions[1], $like_conditions[2]];
        }
        if($model->main_category) {
            $additional_category_conditions = ["LIKE","ads.categories_list","|$model->main_category|"];
        }
        $ads = [];
        if($ads_list) {
            $ads = Ads::find()
                ->where($additional_category_conditions)
                ->andWhere($user_conditions)
                ->andWhere($where_conditions)
                ->andWhere($expired_conditions)
                ->andWhere($add_like_conditions)
                ->andWhere($add_location_conditions)
                ->andWhere($add_active_conditions)
                ->orderBy($model->sorting)
                ->offset(($model->page - 1)* $model->limit)
                ->limit($model->limit)
                ->all();
        }

        $count = Ads::find()
            ->where($additional_category_conditions)
            ->andWhere($location_conditions)
            ->andWhere($user_conditions)
            ->andWhere($where_conditions)
            ->andWhere($add_expired_conditions)
            ->andWhere($add_active_conditions)
            ->andWhere($add_like_conditions)
            ->count();

        $price_range =  (new \yii\db\Query())
            ->select('MAX(price) as max, MIN(price) as min')
            ->from('ads')
            ->where($additional_category_conditions)
            ->andWhere($location_conditions)
            ->one();

        $views_expired_conditions = ['>', 'expiry_date', time()];
        $views_amount =  (new \yii\db\Query())
                    ->from('ads_views')
                    ->where(['in', 'ads_id',
                        (new \yii\db\Query())
                            ->select('id')
                            ->from('ads')
                            ->andFilterWhere($user_conditions)
                            ->andWhere($where_conditions)
                            ->andFilterWhere($active_conditions)
                            ->andFilterWhere($views_expired_conditions)
                            ->andFilterWhere($location_conditions)
                            ->andFilterWhere($category_conditions)
                            ->andFilterWhere($like_conditions)
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
    public function getHumanDate($date_type = self::DATE_TYPE_CREATION){
        $date = null;
        switch($date_type){
            case self::DATE_TYPE_UPDATING :
                $date = $this->updated_at;
                break;
            case self::DATE_TYPE_EXPIRATION :
                $date = $this->expiry_date;
                break;
            default:
                $date = $this->created_at;
                break;
        }
        $ad_day_number = date('z', $date);
        $today_number = date('z', time());
        $daystr = '';
        if($ad_day_number == $today_number and date('y', $date) == date('y', time())){
            $daystr .= __('Today');
        }else if($today_number == (1 + $ad_day_number) and date('y', $date) == date('y', time())){
            $daystr .= __('Yesterday');
        }else{
            $daystr .= date('d:m:y', $date);
        }
        $daystr .= ' '.date('H:i', $date);
        return $daystr;
    }

    function getBreadcrumbs(){
        $parent = $this->category;
        $breadcrumbs = [];
        $breadcrumbs[] = ['label' => $this->title, 'link' => $this->city->domain."/".$this->url, 'use_cookie' => false  ];
        while ($parent) {
            $breadcrumbs[] = ['label' => $parent->_text->name, 'link' => $this->city->domain.'/'.$parent->_text->url."/", 'use_cookie' => false];
            $parent = $parent->getParent()->one();
        }
        return array_reverse($breadcrumbs);
    }

    static function generateApplicationUrl(){
        $category = Yii::$app->request->get('category');
        $placement = Yii::$app->request->get('placement');
        $application_url = self::DEFAULT_LINK;
        if($placement){
            $placement_found = PlacementsText::find()->where(['url' => $placement])->one();
            if($placement_found and $placement_found->application_url and $placement_found->application_url != ""){
                $application_url = $placement_found->application_url;
            }
        }else{
            if($category){
                $category_found = CategoriesText::find()->where(['url'=>$category])->one();
                if($category_found and $category_found->application_url and $category_found->application_url != ""){
                    $application_url = $category_found->application_url;
                }
            }
        }
        return $application_url."/";
    }

    /**
     * Визвращает ид-шники всех категорий (и их родителей) обьявления
     * @return array
     */
    public function getAllCategoriesIds(){
        $ids = [];
        $arr = explode("||",$this->categories_list);
        foreach($arr as $item){
            $item = str_replace("|", "", $item);
            $ids[] = $item;
        }
        return $ids;
    }
}
