<?php
namespace frontend\models;

use common\models\AdCategory;
use common\models\Ads;
use common\models\Category;
use common\models\CategoryAd;
use common\models\City;
use common\models\CounterCategory;
use common\models\CounterCityCategory;
use common\models\Placement;
use common\models\User;
use frontend\components\Location;
use frontend\controllers\UsersController;
use frontend\helpers\TransliterationHelper;
use Yii;
use yii\base\Model;
use common\models\Mailer;
use common\models\Files;

/**
 * New Add form
 */
class NewAdForm extends Model
{
    public $id;
    public $email;
    public $name;
    public $phone;
    public $placement_id;
    public $expiry_date;
    public $cities_id;
    public $title;
    public $text;
    public $price;
    public $agreement;
    public $categories_list;
    public $files = [];
    public $categories = [];
    const MESSAGE_FAILED = 'failed';
    const MESSAGE_SUCCESS = 'success';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['files'], 'safe'],
            [['email', 'name', 'phone', 'categories_list'], 'string'],
            [[
                'categories',
                'placement_id',
                'expiry_date',
                'title',
                'text',
                'agreement',
                'price',
                'cities_id'], 'required', 'message' => __('Required field')],
            [['text'], 'string', 'max' => 1000, 'tooLong' => __("Fields must not be more than 1000 chars long")],
            [['title'], 'string', 'max' => 100, 'tooLong' => __("Fields must not be more than 100 chars long")],
            [['cities_id'], 'integer', 'integerOnly' => true, 'min' => 1, 'tooSmall' => __('Required field')],
            [['placement_id'], 'integer', 'integerOnly' => true, 'min' => 1, 'tooSmall' => __('Required field')],
            [['expiry_date'], 'string'],
            [['agreement'], 'integer', 'integerOnly' => true, 'max' => 1],
            [['price'], 'integer', 'message' => __('Incorrect format')],
            [['price'], 'integer', 'min' => 0 ,'max' => 99999999999, 'tooBig' => __("Fields must not be more than 11 chars long")],
            ['email','email', 'message' => __('Incorrect email')],
            ['name', "validateName" ],
            ['phone', "validatePhone" ],
            ['phone', "string", 'max' => 16, 'tooLong' => __('Fields must not be more than 16 chars long') ],
            ['id', "integer" ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cities_id' => 'City',
            'users_id' => 'User',
            'categories_id' => 'Category',
            'title' => 'Заголовок',
            'text' => 'Описание',
            'price' => 'Price',
            'email' => 'Email',
            'phone' => 'Phone',
            'name' => 'name',
            'agreement' => 'agreement',
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validatePhone($attribute, $params){
        if(isset($this->phone)) {
            if (!is_numeric($this->phone)) {
                $this->addError($attribute, __('Phone number must contain digits only'));
            }
            if (strlen($this->phone ) < RegistrForm::PHONE_NUMBER_MIN_LENGTH) {
                $this->addError($attribute, __('Min length is ') . RegistrForm::PHONE_NUMBER_MIN_LENGTH);
            }
        }
    }


    public function validateName($attribute, $params){
        if(isset($this->name)){
            if(strlen($this->name) < 2){
                $this->addError($attribute, __("Too short value"));
            }
        }
    }
    public function newAd(){

        $adsModel = $this->id ? Ads::findOne($this->id) : new Ads();
        if(!$this->id) {
            $adsModel->created_at = time();
        }
        $adsModel->cities_id = $this->cities_id;
        if(!$this->id) { //если нет ID значти это не редактирование, а создание обьявления
            if(!isset(\Yii::$app->user->identity)){
                $user = User::find()->where(['email' => $this->email])->one();
                if($user){
                    $user_id = $user->id;
                }else {// если мы не находим юзера с таким емейлом, то создаем нового
                    $user = new User();
                    $user->cities_id = $this->cities_id;
                    $user->email = $this->email;
                    $name_arr = explode(" ", $this->name);
                    $user->first_name = $name_arr[0];
                    $user->last_name = (isset($name_arr[1])) ? $name_arr[1] : null;
                    $user->phone_number = $this->phone;
                    $password = generateRandomString();
                    $user->setPassword($password);
                    $user->save();
                    $user_id = $user->id;
                }
                $ad_with_token = Ads::find()
                    ->where(['users_id' => $user->id])
                    ->andWhere(['not', ['session_token' => null]])
                    ->one();
                if($ad_with_token){ //если у этого неавторизованного юзера уже есть обьявление, то берем сессионый токен из него, для того чтоб он мог редактировать его не авотризуясь
                    $adsModel->session_token = $ad_with_token->session_token;
                }else{// если нет обьявления - значит генерим его
                    $adsModel->session_token = base64_encode(time()."-".$this->email);
                }
            }else{
                $user_id = \Yii::$app->user->identity->id;
            }
            $adsModel->users_id = $user_id;
        }
        $adsModel->categories_id = $this->categories[0];
        $adsModel->title = $this->title;
        $adsModel->text = $this->text;
        $adsModel->price = $this->price;
        $expiry_date = date_create(date('Y-m-d h:i:s',$adsModel->created_at));
        switch($this->expiry_date){
            case Ads::DATE_RANGE_ONE_MONTH :
                date_add($expiry_date, date_interval_create_from_date_string('1 month'));
                break;
            case Ads::DATE_RANGE_THREE_MONTHS :
                date_add($expiry_date, date_interval_create_from_date_string('3 months'));
                break;
            case Ads::DATE_RANGE_SIX_MONTHS :
                date_add($expiry_date, date_interval_create_from_date_string('6 months'));
                break;
            case Ads::DATE_RANGE_ONE_YEAR :
                date_add($expiry_date, date_interval_create_from_date_string('1 year'));
                break;
            case Ads::DATE_RANGE_TWO_YEARS :
                date_add($expiry_date, date_interval_create_from_date_string('2 years'));
                break;
            case Ads::DATE_RANGE_THREE_YEARS :
                date_add($expiry_date, date_interval_create_from_date_string('3 years'));
                break;
            case Ads::DATE_RANGE_UNLIMITED :
                date_add($expiry_date, date_interval_create_from_date_string('20 years'));
                break;
            default :
                date_add($expiry_date, date_interval_create_from_date_string('1 month'));
                break;

        }
        $expiry_date = strtotime(date_format($expiry_date, 'Y-m-d h:i:s'));
        $adsModel->expiry_date = $expiry_date;
        $adsModel->placements_id = $this->placement_id;
        $adsModel->url = $adsModel->generateUniqueUrl($this->title);
        $adsModel->save();
        $adsModel->url = $adsModel->url."-".$adsModel->id;
        $adsModel->save();

        if(isset($_POST['files'])) {
            Files::linkFilesToModel($_POST['files'], $adsModel);
        }
        if($this->id){//если это редактирование, то удалим все связки с категориями что бы после перезаписать
            $ac = AdCategory::find()->where(['ads_id'=>$this->id])->all();
            foreach($ac as $i){
                $i->delete();
            }
            $ca = CategoryAd::find()->where(['ads_id'=>$this->id])->all();
            foreach($ca as $i){
                $i->delete();
            }

            $categories_ids = $adsModel->getAllCategoriesIds();
            foreach($categories_ids as $c_id){
                // уменьшаем на 1 каунтеры категорий
                $cat_counter = CounterCategory::find()->where(['categories_id' => $c_id, 'countries_id' => $adsModel->city->region->country->id])->one();
                $cat_counter->ads_amount = $cat_counter->ads_amount - 1;
                $cat_counter->save();
                // уменьшаем на 1 каунтеры для город+категория
                $city_cat_counter = CounterCityCategory::find()->where(['categories_id' => $c_id, 'cities_id' => $adsModel->cities_id])->one();
                $city_cat_counter->ads_amount = $city_cat_counter->ads_amount - 1;
                $city_cat_counter->save();
                //уменьшаем на 1 каунтеры для городa
                $city = City::find()->where(['id' => $adsModel->cities_id])->one();
                $city->ads_amount = $city->ads_amount - 1;
                $city->save();
            }
            //очистим строку ads.categories_list дабы потом ее перезаписать
            $adsModel->categories_list = null;
            $adsModel->save();
        }

        $parents_arr = [];
        foreach($this->categories as $k => $cat){
            $category_model = Category::find()->where(['id'=>$cat])->one();
            $parents = $category_model->getAllParents();
            foreach($parents as $parent){
                if(!isset($parents_arr[$parent['id']]) AND $parent['id'] != '') {
                    $parents_arr[$parent['id']] = $parent;
                }
            }
            if($k > 0) {
                $adCategory = new AdCategory();
                $adCategory->ads_id = $adsModel->id;
                $adCategory->categories_id = $cat;
                $adCategory->save();
            }
        }
        $id_str = '';
        foreach($parents_arr as $id => $parent){
            $category_ad = new CategoryAd();
            $category_ad->categories_id = $id;
            $category_ad->ads_id = $adsModel->id;
            $category_ad->save();
            // counter для категории
            $cat_counter = CounterCategory::find()->where(['categories_id' => $id, 'countries_id' => $adsModel->city->region->country->id])->one();
            if(!$cat_counter){
                $cat_counter = new CounterCategory();
                $cat_counter->categories_id = $id;
                $cat_counter->countries_id = $adsModel->city->region->country->id;
                $cat_counter->ads_amount = 1;
            }else{
                $cat_counter->ads_amount = $cat_counter->ads_amount + 1;
            }
            $cat_counter->save();
            //counter для катагории + город
            $city_cat_counter = CounterCityCategory::find()->where(['categories_id' => $id, 'cities_id' => $adsModel->cities_id])->one();
            if(!$city_cat_counter){
                $city_cat_counter = new CounterCityCategory();
                $city_cat_counter->cities_id = $adsModel->cities_id;
                $city_cat_counter->categories_id = $id;
                $city_cat_counter->ads_amount = 1;
            }else{
                $city_cat_counter->ads_amount = $city_cat_counter->ads_amount + 1;
            }
            $city_cat_counter->save();
            $id_str .= "|$id|";
        }

        $adsModel->categories_list = $id_str;
        $adsModel->save();
        //каунтер для городов
        $city = City::find()->where(['id' => $adsModel->cities_id])->one();
        $city->ads_amount = $city->ads_amount ? $city->ads_amount + 1 : 1;
        $city->save();

        if(!isset(\Yii::$app->user->identity)){
            if(isset($password)) {
                Mailer::send($user->email, __("Add applied"), 'add-published', ['user' => $user, 'url' => "https://" . Location::getCurrentDomain() . "/" . $adsModel->url(), "pass" => $password, "fast" => true, 'add' => $adsModel]);
            }
        }else {
            if(!$this->id) {
                Mailer::send(Yii::$app->user->identity->email, __('Add successfully added.'), 'add-published', ['user' => Yii::$app->user->identity, 'add' => $adsModel, "fast" => false]);
            }
        }
        return $adsModel;
    }
}
