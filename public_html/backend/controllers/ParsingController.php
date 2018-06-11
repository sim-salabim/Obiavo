<?php
namespace backend\controllers;

use common\helpers\JsonData;
use common\models\CategoriesRaw;
use common\models\CategoriesText;
use common\models\Category;
use common\models\CategoryPlacement;
use common\models\CategoryPlacementText;
use common\models\libraries\TelegrammLoging;
use common\models\ParsingCategoriesText;
use common\models\ParsingCategory;
use common\models\ParsingCategoryPlacement;
use common\models\ParsingCategoryPlacementText;
use common\models\Settings;
use frontend\helpers\TransliterationHelper;
use Yii;
use yii\base\Exception;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class ParsingController extends BaseController
{

    private $token = "14a019de-e999-4fe1-afe0-661f59b47d39";
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $setting = Settings::find()->all();

        return $this->render('index',compact('setting'));
    }

    public function actionCategories()
    {
        $categories_amount = (new Query())
            ->select('id')
            ->from('parsing_categories')
            ->count();
        return $this->render('categories', ['categories_amount' => $categories_amount]);
    }

    public function actionCategoriesLiveTables()
    {
        $categories_amount = (new Query())
            ->select('id')
            ->from('parsing_categories')
            ->count();
        return $this->render('categories-live-tables', ['categories_amount' => $categories_amount]);
    }

    public function actionPlacement(){
        $placements_amount = (new Query())
            ->select('id')
            ->from('parsing_categories_placement_text')
            ->count();
        return $this->render('placement', ['placements_amount' => $placements_amount]);
    }

    public function actionPlacementParsing(){
        if(Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if(!isset($post['limit']) OR !isset($post['offset']) OR !isset($post['amount'])){
                Yii::$app->response->statusCode = 500;
                return ['error' => "Parameters missed", "post" => $post];
            }else{
                $placements = ParsingCategoryPlacementText::find()->offset($post['offset'])->limit($post['limit'])->orderBy('id ASC')->all();
                $parsed = $post['parsed'];
                foreach($placements as $pl){
                    $category_text_raw = CategoriesRaw::find()->where(['categories_id' => $pl->categories_id])->one();
                    $category_placement = new CategoryPlacement();
                    $category_placement->placements_id = $pl->placements_id;
                    $category_placement->categories_id = $pl->categories_id;
                    $category_placement->save();
                    $parsed_placement = new CategoryPlacementText();
                    $parsed_placement->category_placement_id = $category_placement->id;
                    $parsed_placement->languages_id = 1;
                    $p_vp = ($category_text_raw->name_p_vp) ? $category_text_raw->name_p_vp : $category_text_raw->name;
                    $p_rp = ($category_text_raw->name_p_rp) ? $category_text_raw->name_p_rp : $category_text_raw->name;
                    $p  = ($category_text_raw->name_p_ip) ? $category_text_raw->name_p_ip : $category_text_raw->name;
                    $seo_title = str_replace(["{key:name-vp-s}", "{key:name-rp-s}"], [$p_vp, $p_rp], $pl->seo_title);
                    $parsed_placement->seo_title = $seo_title;
                    $vp = ($category_text_raw->name_vp) ? $category_text_raw->name_vp : $category_text_raw->name;
                    $seo_h1 = str_replace(["{key:name}", "{key:name-vp}", "{key:name-translit}"], [ $category_text_raw->name,$vp,TransliterationHelper::transliterate($category_text_raw->name)], $pl->seo_h1);
                    $parsed_placement->seo_h1 = $seo_h1;
                    $seo_h2 = str_replace(["{key:name}", "{key:name-rp-s}"], [ $category_text_raw->name,$p_rp], $pl->seo_h2);
                    $parsed_placement->seo_h2 = $seo_h2;
                    $parsed_placement->name = $pl->name;
                    $parsed_placement->seo_text = $pl->seo_text;
                    $seo_desc = str_replace(["{key:name-rp-s}","{key:name-rp-s}"],[$p_rp, $p],$pl->seo_desc);
                    $parsed_placement->seo_desc = $seo_desc;
                    $keywords = str_replace(["{key:name-vp-s","{key:name-rp-s}","{key:name-translit}"], [$p_vp, $p_rp,TransliterationHelper::transliterate($category_text_raw->name)], $pl->seo_keywords);
                    $parsed_placement->seo_keywords = $keywords;
                    $parsed_placement->save();
                    $parsed++;
                }
            }
            $persantage = $post['offset'] * 100/$post['amount'];
            $persentage = floor($persantage);

            Yii::$app->response->statusCode = 200;
            return ["persantage" => $persentage, 'parsed' => $parsed, 'offset' => $post['offset'] + 1];
        }
    }

    public function actionCategoriesParsingLiveTables(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if(!isset($post['limit']) OR !isset($post['offset']) OR !isset($post['amount'])){
                Yii::$app->response->statusCode = 500;
                return ['error' => "Parameters missed", "post" => $post];
            }else{
                $categories = ParsingCategory::find()->offset($post['offset'])->limit($post['limit'])->orderBy('id ASC')->all();
                $parsed = $post['parsed'];
                foreach($categories as $cat){
                    $cat_text = ParsingCategoriesText::find()->where(['categories_id' => $cat->id, 'languages_id' => 1])->one();
                    $category = Category::find()->where(["id" => $cat->id]);
                    $category_raw = CategoriesRaw::find()->where(['categories_id' => $cat->id])->one();
                    if(!isset($category->id)){
                        $category = new Category();
                    }
                    $category->techname = $cat->name;
                    $category->name = $cat->name;
                    $category->active = $cat->active;
                    $category->social_networks_groups_main_id = $cat->social_networks_groups_main_id;
                    $category->brand = $cat->brand;
                    $category->excel_id = $cat->excel_id;
                    $category->seo_id = $cat->seo_id;
                    $category->clean_harakterisitka = $cat->clean_harakterisitka;
                    $category->href = $cat->href;
                    $category->href_id = $cat->href_id;
                    $category->parent_id = ($cat->parent_id == 0) ? null : $cat->parent_id;
                    $category->save();
                    $category_text = CategoriesText::find()->where(['categories_id' => $category->id, "languages_id" => 1])->one();
                    if(!$category_text){
                        $category_text = new CategoriesText();
                    }
                    $category_text->categories_id = $category->id;
                    $category_text->languages_id = 1;
                    $category_text->url = TransliterationHelper::transliterate($cat->name);
                    $category_text->seo_h1 = str_replace(["{key:name}", "{key:name-translit}"], [$category_raw->name, TransliterationHelper::transliterate($cat->name)], $cat_text->seo_h1);
                    $category_text->seo_title = str_replace("{key:name}", $category_raw->name, $cat_text->seo_title);
                    $vp_s = ($category_raw->name_p_vp) ? $category_raw->name_p_vp : $category_raw->name;
                    $category_text->seo_h2 = str_replace(["{key:name-vp-s}", "{key:name}"], [$vp_s, $category_raw->name], $cat_text->seo_h2);
                    $category_text->name = $category_raw->name;
                    $pp_s = ($category_raw->name_p_pp) ? $category_raw->name_p_pp : $category_raw->name;
                    $category_text->seo_desc = mb_strtolower(str_replace(["{key:name-pp-s}", "{key:name}"], [$pp_s, $category_raw->name], $cat_text->seo_desc));
                    $name_s = ($category_raw->name_p_ip) ? $category_raw->name_p_ip : $category_raw->name_ip;
                    $category_text->seo_keywords = mb_strtolower(str_replace(["{key:name}", "{key:name-vp-s}", "{key:name-s}", "{key:name-translit}"] ,[$category_raw->name, $vp_s, $name_s, TransliterationHelper::transliterate($category_raw->name)], $cat_text->seo_keywords));
                    $category_text->save();
                    $parsed++;
                }
            }
            $persantage = $post['offset'] * 100/$post['amount'];
            $persentage = floor($persantage);

            Yii::$app->response->statusCode = 200;
            return ["persantage" => $persentage, 'parsed' => $parsed, 'offset' => $post['offset'] + 1];
        }
    }

    public function actionCategoriesParsing()
    {
        if(Yii::$app->request->isPost){
            $url = "http://ws3.morpher.ru/russian/declension?token=C6F4FAA1-E738-4D94-95D4-71A0F6C6813C&";
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if(!isset($post['limit']) OR !isset($post['offset']) OR !isset($post['amount'])){
                Yii::$app->response->statusCode = 500;
                return ['error' => "Parameters missed", "post" => $post];
            }else{
                $categories = \common\models\ParsingCategory::find()->offset($post['offset'])->limit($post['limit'])->orderBy('id ASC')->all();
                $parsed = $post['parsed'];
                foreach($categories as $cat){
                    $url = "http://ws3.morpher.ru/russian/declension?token=".$this->token."&";
                    $parsingCatText = \common\models\ParsingCategoriesText::find()->where(['categories_id'=> $cat->id, "languages_id" => 1])->one();

                    $string = $parsingCatText->name;
                    $string = urlencode($string);
                    $url .= "s=".$string;
                    $result = $this->getContent($url);
                    $xml = simplexml_load_string($result);
                    $category_raw = \common\models\CategoriesRaw::find()->where(["categories_id" => $cat->id])->one();
                    if (!isset($category_raw->id)) {
                        $category_raw = new \common\models\CategoriesRaw();
                    }
                    $category_raw->categories_id = $cat->id;
                    $category_raw->name = $cat->name;
                    if(!isset($xml->code)) {
                        $plural = null;
                        if(isset($xml->множественное)){
                            $plural = (array)$xml->множественное;
                        }
                        $xml = (array)$xml;
                        $category_raw->name_ip = $cat->name;
                        $category_raw->name_rp = isset($xml['Р']) ? $xml['Р'] : null;
                        $category_raw->name_dp = isset($xml['Д']) ? $xml['Д'] : null;
                        $category_raw->name_vp = isset($xml['В']) ? $xml['В'] : null;;
                        $category_raw->name_tp = isset($xml['Т']) ? $xml['Т'] : null;
                        $category_raw->name_pp = isset($xml['П']) ? $xml['П'] : null;
                        $category_raw->name_pp_about = isset($xml['П_о']) ? $xml['П_о'] : null;
                        $category_raw->name_where = isset($xml['где']) ? $xml['где'] : null;
                        $category_raw->name_to = isset($xml['куда']) ? $xml['куда'] : null;
                        $category_raw->name_from = isset($xml['откуда']) ? $xml['откуда'] : null;
                        if (count($plural)) {
                            $category_raw->name_p_ip = $plural['И'];
                            $category_raw->name_p_rp = $plural['Р'];
                            $category_raw->name_p_dp = $plural['Д'];
                            $category_raw->name_p_vp = $plural['В'];
                            $category_raw->name_p_tp = $plural['Т'];
                            $category_raw->name_p_pp = $plural['П'];
                            $category_raw->name_p_pp_about = $plural['П_о'];
                        }
                        if (isset($xml['род']) AND $xml['род'] == "Множественное") {
                            $category_raw->name_p_pp_about = $xml['П_о'];
                        }

//                    $category->save();
//                    $text = ParsingCategoriesText::find()->where(["categories_id"=>$cat->id])->one();
//                    $category->_text->languages_id = $cat->languages_id;
                    }else{
                        //TelegrammLoging::send("Пизда всему, код ошибки ".$xml->code." в слове: ".$parsingCatText->name." $xml->message");
                    }
                    $category_raw->save();
                    $parsed++;
                }
                $persantage = $post['offset'] * 100/$post['amount'];
                $persentage = floor($persantage);

                Yii::$app->response->statusCode = 200;
                return ["persantage" => $persentage, 'parsed' => $parsed, 'offset' => $post['offset'] + 1];
            }
        }
    }

    private function getContent($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $result = trim(curl_exec($ch));
        curl_close($ch);
        return $result;
    }

    public function actionUpdate() {

        $setting = Settings::find()->one();


        $toUrl = Url::toRoute(['save']);

        return $this->renderAjax('form',compact('setting', 'toUrl'));
    }

    public function actionCreate() {
        $setting = new Settings();

        $toUrl = Url::toRoute(['save']);

        return $this->renderAjax('form',  compact('setting','toUrl'));
    }

    public function actionSave() {
        $post = Yii::$app->request->post();
        $setting = Settings::find()->one();
        if (!$setting){
            $setting = new Settings();
        }
        $setting->load($post);
        if (!$setting->save()){
            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $setting->getErrors(),
            ]);
        }
        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Успешно сохранено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete(){

        $setting = Settings::find()->one();
        $setting->delete();

        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Успешно удалено",
            JsonData::REFRESHPAGE => '',
        ]);
    }

}