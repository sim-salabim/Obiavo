<?php
namespace frontend\models;

use common\models\AdCategory;
use common\models\Ads;
use common\models\Category;
use common\models\CategoryAd;
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
            [['email', 'name', 'phone'], 'string'],
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
            ['email','email', 'message' => __('Incorrect email')],
            ['name', "validateName" ],
            ['phone', "validatePhone" ],
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
        if(!isset(\Yii::$app->user->identity)){
            $user = User::find()->where(['email' => $this->email])->one();
            if($user){
                $user_id = $user->id;
            }else {
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
        }else{
            $user_id = \Yii::$app->user->identity->id;
        }
        $adsModel = new Ads();
        $adsModel->created_at = time();
        $adsModel->cities_id = $this->cities_id;
        $adsModel->users_id = $user_id;
        $adsModel->categories_id = $this->categories[0];
        $adsModel->title = $this->title;
        $adsModel->text = $this->text;
        $adsModel->price = $this->price;
        $expiry_date = null;
        switch($this->expiry_date){
            case Ads::DATE_RANGE_ONE_MONTH :
                $expiry_date = strtotime(" +1 month");
                break;
            case Ads::DATE_RANGE_THREE_MONTHS :
                $expiry_date = strtotime(" +3 months");
                break;
            case Ads::DATE_RANGE_SIX_MONTHS :
                $expiry_date = strtotime(" +6 months");
                break;
            case Ads::DATE_RANGE_ONE_YEAR :
                $expiry_date = strtotime(" +1 year");
                break;
            case Ads::DATE_RANGE_TWO_YEARS :
                $expiry_date = strtotime(" +2 years");
                break;
            case Ads::DATE_RANGE_THREE_YEARS :
                $expiry_date = strtotime(" +3 years");
                break;
            case Ads::DATE_RANGE_UNLIMITED :
                $expiry_date = strtotime(" +100 years");
                break;
            default :
                $expiry_date = strtotime(" +1 month");
                break;

        }
        $adsModel->expiry_date = $expiry_date;
        $adsModel->placements_id = $this->placement_id;
        $adsModel->url = $adsModel->generateUniqueUrl($this->title);
        $adsModel->save();
        $adsModel->url = TransliterationHelper::transliterate($this->title)."-".$adsModel->id;
        $adsModel->save();
        if(isset($_POST['files'])) {
            Files::linkFilesToModel($_POST['files'], $adsModel);
        }
        $parents_arr = [];
        foreach($this->categories as $k => $cat){
            $category_model = Category::find()->where(['id'=>$cat])->one();
            $parents = $category_model->getAllParents();
            foreach($parents as $parent){
                $parents_arr[$parent['id']] = $parent;
            }
            if($k > 0) {
                $adCategory = new AdCategory();
                $adCategory->ads_id = $adsModel->id;
                $adCategory->categories_id = $cat;
                $adCategory->save();
            }
        }
        foreach($parents_arr as $id => $parent){
            $category_ad = new CategoryAd();
            $category_ad->categories_id = $id;
            $category_ad->ads_id = $adsModel->id;
            $category_ad->save();
        }
        if(!isset(\Yii::$app->user->identity)){
            if(isset($password)) {
                Mailer::send($user->email, __("Add applied"), 'add-published', ['user' => $user, 'url' => "https://" . Location::getCurrentDomain() . "/" . $adsModel->url(), "pass" => $password, "fast" => true, 'add' => $adsModel]);
            }
        }else {
            Mailer::send(Yii::$app->user->identity->email, __('Add successfully added.'), 'add-published', ['user' => Yii::$app->user->identity, 'add' => $adsModel, "fast" => false]);
        }
        return $adsModel;
    }
}
