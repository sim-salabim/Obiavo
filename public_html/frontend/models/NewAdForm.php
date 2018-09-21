<?php
namespace frontend\models;

use common\models\AdCategory;
use common\models\Ads;
use common\models\Placement;
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
    public $placement_id;
    public $expiry_date;
    public $cities_id;
    public $title;
    public $text;
    public $price;
    public $files = [];
    public $categories = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['files'], 'safe'],
            [[
                'categories',
                'placement_id',
                'expiry_date',
                'title',
                'text',
                'price',
                'cities_id'], 'required', 'message' => __('Required field')],
            [[
                'placement_id',
                'expiry_date',
                'cities_id'], 'integer', 'integerOnly' => true, 'min' => 1],
            [['expiry_date','price'], 'integer', 'message' => __('Incorrect format')],
        ];
    }

    public function newAd(){
        $adsModel = new Ads();
        $adsModel->created_at = time();
        $adsModel->cities_id = $this->cities_id;
        $adsModel->users_id = \Yii::$app->user->identity->id;
        $adsModel->categories_id = $this->categories[0];
        $adsModel->title = $this->title;
        $adsModel->text = $this->text;
        $adsModel->price = $this->price;
        $adsModel->expiry_date = time() + $this->expiry_date;
        $adsModel->placements_id = $this->placement_id;
        $adsModel->url = $adsModel->generateUniqueUrl($this->title);
        $adsModel->save();
        $adsModel->url = $adsModel->id."-".TransliterationHelper::transliterate($this->title);
        $adsModel->save();
        if(isset($_POST['files'])) {
            Files::linkFilesToModel($_POST['files'], $adsModel);
        }
        foreach($this->categories as $k => $cat){
            if($k > 0) {
                $adCategory = new AdCategory();
                $adCategory->ads_id = $adsModel->id;
                $adCategory->categories_id = $cat;
                $adCategory->save();
            }
        }
        Mailer::send(Yii::$app->user->identity->email, __('Add successfully added.'), 'add-published', ['user' => Yii::$app->user->identity, 'add' => $adsModel]);
        return $adsModel;
    }

}
