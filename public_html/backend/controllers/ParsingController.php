<?php
namespace backend\controllers;

use common\helpers\JsonData;
use common\models\CategoriesRaw;
use common\models\CategoriesText;
use common\models\Category;
use common\models\CategoryPlacement;
use common\models\CategoryPlacementText;
use common\models\H2CategoriesText;
use common\models\libraries\TelegrammLoging;
use common\models\ParsingCategoriesText;
use common\models\ParsingCategory;
use common\models\ParsingCategoryPlacement;
use common\models\ParsingCategoryPlacementText;
use common\models\ParsingCategoryRaw;
use common\models\ParsingSeoRaw;
use common\models\PH2CategoriesText;
use common\models\Settings;
use frontend\helpers\TransliterationHelper;
use Yii;
use yii\base\Exception;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\HttpException;

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
    public function actionCategoriesText(){
        $categories_amount = (new Query())
            ->select('id')
            ->from('parsing_categories_text')
            ->count();
        return $this->render('categories-text', ['categories_amount' => $categories_amount]);
    }
    public function actionCategoriesPlacementText(){
        $categories_amount = (new Query())
            ->select('id')
            ->from('parsing_categories_placement_text')
            ->count();
        return $this->render('categories-placements-text', ['categories_amount' => $categories_amount]);
    }
    public function actionCategories()
    {
        $categories_amount = (new Query())
            ->select('id')
            ->from('parsing_categories')
            ->count();
        return $this->render('categories', ['categories_amount' => $categories_amount]);
    }
    public function actionH()
    {
        $categories_amount = (new Query())
            ->select('id')
            ->from('parsing_categories_text')
            ->count();
        return $this->render('h', ['categories_amount' => $categories_amount]);
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
            if(!isset($post['limit']) OR !isset($post['amount'])){
                Yii::$app->response->statusCode = 500;
                return ['error' => "Parameters missed", "post" => $post];
            }else{
                $parsed = $post['parsed'];
                $placements = ParsingCategoryPlacementText::find()->offset($parsed)->limit($post['limit'])->orderBy('id ASC')->all();
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
                    $parsed_placement->seo_title = $pl->seo_title;
                    $vp = ($category_text_raw->name_vp) ? $category_text_raw->name_vp : $category_text_raw->name;
                    $seo_h1 = str_replace(["{key:name}", "{key:name-vp}", "{key:name-translit}"], [ $category_text_raw->name,$vp,TransliterationHelper::transliterate($category_text_raw->name)], $pl->seo_h1);
                    $parsed_placement->seo_h1 = $seo_h1;
                    $seo_h2 = str_replace(["{key:name}", "{key:name-rp-s}"], [ $category_text_raw->name,$p_rp], $pl->seo_h2);
                    $parsed_placement->seo_h2 = $pl->seo_h2;
                    $parsed_placement->name = $pl->name;
                    $parsed_placement->seo_text = $pl->seo_text;
                    $seo_desc = str_replace(["{key:name-rp-s}","{key:name-rp-s}"],[$p_rp, $p],$pl->seo_desc);
                    $parsed_placement->seo_desc = $pl->seo_desc;
                    $keywords = str_replace(["{key:name-vp-s","{key:name-rp-s}","{key:name-translit}"], [$p_vp, $p_rp,TransliterationHelper::transliterate($category_text_raw->name)], $pl->seo_keywords);
                    $parsed_placement->seo_keywords = $pl->seo_keywords;
                    $parsed_placement->save();
                    $parsed++;
                }
            }
            $persantage = $parsed * 100/$post['amount'];
            $persentage = floor($persantage);

            Yii::$app->response->statusCode = 200;
            return ["persantage" => $persentage, 'parsed' => $parsed];
        }
    }

    public function actionCategoriesParsingLiveTables(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if(!isset($post['limit']) OR !isset($post['amount'])){
                Yii::$app->response->statusCode = 500;
                return ['error' => "Parameters missed", "post" => $post];
            }else{
                $categories = ParsingCategory::find()->offset($post['parsed'])->limit($post['limit'])->orderBy('id ASC')->all();
                $parsed = $post['parsed'];
                foreach($categories as $cat){
                    $cat_text = ParsingCategoriesText::find()->where(['categories_id' => $cat->id, 'languages_id' => 1])->one();
                    //$category = Category::find()->where(["id" => $cat->id]);
                    $category_raw = CategoriesRaw::find()->where(['categories_id' => $cat->id])->one();
                    //if(!isset($category->id)){
                        $category = new Category();
                   // }
                    $category->id = $cat->id;
                    $category->techname = $cat->techname;
                    $category->name = $cat->name;
                    $category->active = $cat->active;
                    $category->social_networks_groups_main_id = $cat->social_networks_groups_main_id;
                    $category->brand = ($cat->brand) ? $cat->brand : 0;
                    $category->excel_id = $cat->excel_id;
                    $category->seo_id = $cat->seo_id;
                    $category->clean_harakterisitka = ($cat->clean_harakterisitka) ? $cat->clean_harakterisitka : 0;
                    $category->href = ($cat->href) ? $cat->href : 0;
                    $category->href_id = $cat->href_id;
                    $category->parent_id = ($cat->parent_id == 0) ? null : $cat->parent_id;
                    $category->save();
                    $category_text = new CategoriesText();
                    $category_text->categories_id = $category->id;
                    $category_text->languages_id = 1;
                    $category_text->url = $cat_text->url;
                    $category_text->seo_h1 = $cat_text->seo_h1;
                    $category_text->seo_title = $cat_text->seo_title;
                    $category_text->seo_h2 = $cat_text->seo_h2;
                    $category_text->name = $category_raw->name;
                    $category_text->seo_desc = $cat_text->seo_desc;
                    $category_text->seo_keywords = $cat_text->seo_keywords;
                    $category_text->save();
                    $parsed++;
                }
            }
            $persantage = $parsed * 100/$post['amount'];
            $persentage = floor($persantage);

            Yii::$app->response->statusCode = 200;
            return ["persantage" => $persentage, 'parsed' => $parsed];
        }
    }


   private function replaceCategorySeo($name, $category_raw){
        $result_array = [];
        $search_array = [
            '{key:name-vp-s}',
            '{key:name-back}',
            '{key:name-translit}',
            '{key:name-back-url}',
            '{key:name}',
            '{key:name-vp}',
            '{key:name-s}',
            '{key:name-pp-s}',
            '{key:name-rp-s}',
            '{key:name-dp-s}',
            '{key:name-back-s}',
            '{key:name-pp-back-s}',
            '{key:name-rp-back-s}',
            '{key:name-vp-back-s}',
            '{key:name-vp-back}',
            '{key:name-back-back}',
            '{key:name-back-back-s}',
            '{key:name-pp-back-back-s}',
            '{key:name-rp-back-back-s}',
            '{key:name-back-back-url}',
            '{key:name-vp-back-back-s}',
            '{key:name-vp-back-back}',
        ];
       $category = ParsingCategory::find()->where(['id' => $category_raw->categories_id])->one();
       if($category->parent_id) {
           $parent_category = ParsingCategory::find()->where(['id' => $category->parent_id])->one();
           $parent_raw = CategoriesRaw::find()->where(['categories_id' => $parent_category->id])->one();
           $url_back = ParsingCategoriesText::find()->where(['categories_id' => $parent_category->id])->one()->url;
           if($parent_category->parent_id){
               $parent_parent_category = ParsingCategory::find()->where(['id' => $parent_category->parent_id])->one();
               $parent_parent_raw = CategoriesRaw::find()->where(['categories_id' => $parent_parent_category->id])->one();
               $url_back_back = ParsingCategoriesText::find()->where(['categories_id' => $parent_parent_category->id])->one()->url;
           }
       }
       foreach($search_array as $search){
           $result_array[$search] = '';
           switch($search){
               case '{key:name-vp-s}':
                   $result_array[$search] = ($category_raw->name_vp) ? mb_strtolower($category_raw->name_vp) : mb_strtolower($category_raw->name_ip);
                   break;
               case '{key:name-back}':
                   $result_array[$search] = (isset($parent_raw)) ? $parent_raw->name : '';
                   break;
               case '{key:name-translit}':
                   if(!preg_match('/[^\\p{Common}\\p{Latin}]/u', $name[0])){
                       $result_array[$search] = TransliterationHelper::transliterateToCyrillic($name);
                   }else{
                       $result_array[$search] = $category_raw->name;
                   }
                   break;
               case '{key:name-back-url}':
                   $result_array[$search] = (isset($url_back)) ? $url_back : '';
                   break;
               case '{key:name}':
                   $result_array[$search] = $category_raw->name_ip;
                   break;
               case '{key:name-vp}':
                   $result_array[$search] = ($category_raw->name_vp) ? $category_raw->name_vp : $category_raw->name_ip;
                   break;
               case '{key:name-s}':
                   $result_array[$search] = mb_strtolower($category_raw->name_ip);
                   break;
               case '{key:name-pp-s}':
                   $result_array[$search] = ($category_raw->name_pp_about) ? mb_strtolower($category_raw->name_pp_about) : mb_strtolower("о ".$category_raw->name_ip);
                   break;
               case '{key:name-rp-s}':
                   $result_array[$search] = ($category_raw->name_rp) ? mb_strtolower($category_raw->name_rp) : mb_strtolower($category_raw->name_ip);
                   break;
               case '{key:name-dp-s}':
                   $result_array[$search] = ($category_raw->name_dp) ? mb_strtolower($category_raw->name_dp) : mb_strtolower($category_raw->name_ip);
                   break;
               case '{key:name-back-s}':
                   $result_array[$search] = (isset($parent_raw)) ? mb_strtolower($parent_raw->name) : '';
                   break;
               case '{key:name-pp-back-s}':
                   $result_array[$search] = (isset($parent_raw) and $parent_raw->name_pp_about) ? mb_strtolower($parent_raw->name_pp_about) : '';
                   break;
               case '{key:name-rp-back-s}':
                   $result_array[$search] = (isset($parent_raw) and $parent_raw->name_rp) ? mb_strtolower($parent_raw->name_rp) : '';
                   break;
               case '{key:name-vp-back-s}':
                   $result_array[$search] = (isset($parent_raw) and $parent_raw->name_vp) ? mb_strtolower($parent_raw->name_vp) : '';
                   break;
               case '{key:name-vp-back}':
                   $result_array[$search] = (isset($parent_raw) and $parent_raw->name_vp) ? $parent_raw->name_vp: '';
                   break;
               case '{key:name-back-back}':
                   $result_array[$search] = (isset($parent_parent_raw)) ? $parent_parent_raw->name : '';
                   break;
               case '{key:name-back-back-s}':
                   $result_array[$search] = (isset($parent_parent_raw)) ? mb_strtolower($parent_parent_raw->name) : '';
                   break;
               case '{key:name-pp-back-back-s}':
                   $result_array[$search] = (isset($parent_parent_raw) and $parent_parent_raw->name_pp_about) ? mb_strtolower($parent_parent_raw->name_pp_about) : '';
                   break;
               case '{key:name-rp-back-back-s}':
                   $result_array[$search] = (isset($parent_parent_raw) and $parent_parent_raw->name_rp) ? mb_strtolower($parent_parent_raw->name_rp) : '';
                   break;
               case '{key:name-back-back-url}':
                   $result_array[$search] = (isset($url_back_back)) ? $url_back_back : "";
                   break;
               case '{key:name-vp-back-back-s}':
                   $result_array[$search] = (isset($parent_parent_raw) and $parent_parent_raw->name_vp) ? mb_strtolower($parent_parent_raw->name_vp) : '';
                   break;
               case '{key:name-vp-back-back}':
                   $result_array[$search] = (isset($parent_parent_raw) and $parent_parent_raw->name_vp) ? $parent_parent_raw->name_vp: '';
                   break;
           }

       }
       $replace_arr = [];
       foreach($result_array as $res){
           $replace_arr[] = $res;
       }
       return ['pattern'=>$search_array,'replace'=>$replace_arr ];
    }

    private function makeUrl($url, $idx = 0){
        $search_url = $idx != 0 ? $url."-".$idx : $url;
        $cat_url = ParsingCategoriesText::find()->where(['url' => $search_url])->one();
        if($cat_url){
            $idx++;
            return $this->makeUrl($url, $idx);
        }else{
            return $search_url;
        }
    }

    public function actionCategoriesParsing()
    {
        if(Yii::$app->request->isPost){
            $url = "http://ws3.morpher.ru/russian/declension?token=C6F4FAA1-E738-4D94-95D4-71A0F6C6813C&";
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if(!isset($post['limit']) OR !isset($post['amount'])){
                Yii::$app->response->statusCode = 500;
                return ['error' => "Parameters missed", "post" => $post];
            }else{
                $parsed = $post['parsed'];
                $categories = \common\models\ParsingCategory::find()->offset($parsed)->limit($post['limit'])->orderBy('id ASC')->all();
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
                    $category_raw->name = $parsingCatText->name;
                    if(!isset($xml->code)) {
                        $plural = null;
                        if(isset($xml->множественное)){
                            $plural = (array)$xml->множественное;
                        }
                        $xml = (array)$xml;
                        $category_raw->name_ip = $parsingCatText->name;
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
                        $category_raw->name_ip = $parsingCatText->name;
                        //TelegrammLoging::send("Пизда всему, код ошибки ".$xml->code." в слове: ".$parsingCatText->name." $xml->message");
                    }
                    $category_raw->save();
                    $parsed++;
                }
                $persantage = $parsed * 100/$post['amount'];
                $persentage = floor($persantage);

                Yii::$app->response->statusCode = 200;
                return ["persantage" => $persentage, 'parsed' => $parsed];
            }
        }
    }

    public function actionHParsing()
    {
        if(Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if(!isset($post['limit']) OR !isset($post['amount'])){
                Yii::$app->response->statusCode = 500;
                return ['error' => "Parameters missed", "post" => $post];
            }else{
                $parsed = $post['parsed'];
                $categories_text = ParsingCategoriesText::find()->offset($parsed)->limit($post['limit'])->orderBy('id ASC')->all();
                foreach($categories_text as $text){
                    $h2_broken = H2CategoriesText::find()->where(['id' => $text->id])->one();
                    $h2_broken->seo_h2 = $text->seo_h2;
                    $h2_broken->save();
                    $parsed++;
                }
                $persantage = $parsed * 100/$post['amount'];
                $persentage = floor($persantage);

                Yii::$app->response->statusCode = 200;
                return ["persantage" => $persentage, 'parsed' => $parsed];
            }
        }
    }

    public function actionFixSource(){
        if(Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if (!isset($post['limit']) OR !isset($post['amount'])) {
                Yii::$app->response->statusCode = 500;
                return ['error' => "Parameters missed", "post" => $post];
            } else {
                $categories = ParsingCategoryRaw::find()->offset($post['parsed'])->limit($post['limit'])->orderBy('COL23 ASC')->all();
                $parsed = $post['parsed'];
                foreach($categories as $c){
                    $new_category = new ParsingCategory();
                    $new_category->excel_id = $c->COL23;
                    $new_category->name = $c->COL11;
                    $category_name_arr = $this->getCategoryName($c);

                    $new_category->techname = $category_name_arr['category_name'];
                    $parent_cat_raw = $this->getParentColumn($c, $category_name_arr);
                    if (!$parent_cat_raw) {
                        $new_category->parent_id = null;
                    } else {
                        $parent_cat = ParsingCategory::find()->where(['excel_id' => $parent_cat_raw->COL23])->one();
                        if (!$parent_cat) {
                            throw new HttpException(500, "Parent category not found. " . $parent_cat_raw->COL23);
                        }
                        $new_category->parent_id = $parent_cat->id;
                    }
                    $new_category->brand = $c->COL12;
                    $new_category->save();
                    $new_text = new ParsingCategoriesText();
                    $new_text->categories_id = $new_category->id;
                    $new_text->languages_id = 1;
                    $new_text->name = $category_name_arr['category_name'];
                    $seo_array = $this->makeSeoArray($c);
                    $new_text->seo_h1 = $this->replaceCategorySeo($seo_array['H1'], $c);
                    $new_text->seo_h2 = $this->replaceCategorySeo($seo_array['H2'], $c);
                    $new_text->url = $this->replaceCategorySeo($seo_array['Url'], $c);
                    $new_text->seo_title = $this->replaceCategorySeo($seo_array['Title'], $c);
                    $new_text->seo_desc = $this->replaceCategorySeo($seo_array['Description'], $c);
                    $new_text->seo_keywords = $this->replaceCategorySeo($seo_array['Keywords'], $c);
                    $new_text->seo_text = $this->replaceCategorySeo($seo_array['TEXT'], $c);
                    $new_text->save();
                    $parsed++;
                }
            }
            $persantage = $parsed * 100/$post['amount'];
            $persentage = floor($persantage);

            Yii::$app->response->statusCode = 200;
            return ["persantage" => $persentage, 'parsed' => $parsed];
        }
    }
//TODO запустить
    public function actionCategoriesTextParsing(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if(!isset($post['limit']) OR !isset($post['amount'])){
                Yii::$app->response->statusCode = 500;
                return ['error' => "Parameters missed", "post" => $post];
            }else{
                $categories = ParsingCategory::find()->offset($post['parsed'])->limit($post['limit'])->orderBy('id ASC')->all();
                $parsed = $post['parsed'];
                foreach($categories as $cat){
                    $cat_text = ParsingCategoriesText::find()->where(['categories_id' => $cat->id, 'languages_id' => 1])->one();

                    $category_raw = CategoriesRaw::find()->where(['categories_id' => $cat->id])->one();
                    $replace_array = $this->replaceCategorySeo($cat->techname, $category_raw);
                    $url = TransliterationHelper::transliterate($category_raw->name, true, false);
                    $final_url = $this->makeUrl($url);
                    $cat_text->seo_h1 = str_replace($replace_array['pattern'], $replace_array['replace'], $cat_text->seo_h1);
                    $cat_text->seo_title = str_replace($replace_array['pattern'], $replace_array['replace'], $cat_text->seo_title);
                    $cat_text->seo_h2 = str_replace($replace_array['pattern'], $replace_array['replace'], $cat_text->seo_h2);
                    $cat_text->seo_desc = str_replace($replace_array['pattern'], $replace_array['replace'], $cat_text->seo_desc);
                    $cat_text->seo_keywords = str_replace($replace_array['pattern'], $replace_array['replace'], $cat_text->seo_keywords);
                    $cat_text->seo_text = str_replace($replace_array['pattern'], $replace_array['replace'], $cat_text->seo_text);
                    $replace_array['replace'][2] = $final_url;
                    $replace_array['pattern'][] = '{key:name-url}';
                    $replace_array['replace'][] = $final_url;
                    $cat_text->url = str_replace($replace_array['pattern'], $replace_array['replace'], $cat_text->url);
                    $cat_text->save();
                    $parsed++;
                }
            }
            $persantage = $parsed * 100/$post['amount'];
            $persentage = floor($persantage);

            Yii::$app->response->statusCode = 200;
            return ["persantage" => $persentage, 'parsed' => $parsed];
        }
    }
//TODO запустить 2
    public function actionCategoriesPlacementsTextParsing(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if(!isset($post['limit']) OR !isset($post['amount'])){
                Yii::$app->response->statusCode = 500;
                return ['error' => "Parameters missed", "post" => $post];
            }else{
                $placement_texts = ParsingCategoryPlacementText::find()->offset($post['parsed'])->limit($post['limit'])->orderBy('categories_id ASC')->all();
                $parsed = $post['parsed'];
                foreach($placement_texts as $pl_text){
                    $category = ParsingCategory::find()->where(['id' => $pl_text->categories_id])->one();
                    $category_raw = CategoriesRaw::find()->where(['categories_id' => $pl_text->categories_id])->one();
                    $replace_array = $this->replaceCategorySeo($category->techname, $category_raw);
                    $pl_text->seo_h1 = str_replace($replace_array['pattern'], $replace_array['replace'], $pl_text->seo_h1);
                    $pl_text->seo_title = str_replace($replace_array['pattern'], $replace_array['replace'], $pl_text->seo_title);
                    $pl_text->seo_h2 = str_replace($replace_array['pattern'], $replace_array['replace'], $pl_text->seo_h2);
                    $pl_text->seo_desc = str_replace($replace_array['pattern'], $replace_array['replace'], $pl_text->seo_desc);
                    $pl_text->seo_keywords = str_replace($replace_array['pattern'], $replace_array['replace'], $pl_text->seo_keywords);
                    $pl_text->seo_text = str_replace($replace_array['pattern'], $replace_array['replace'], $pl_text->seo_text);
                    $pl_text->save();
                    $parsed++;
                }
            }
            $persantage = $parsed * 100/$post['amount'];
            $persentage = floor($persantage);

            Yii::$app->response->statusCode = 200;
            return ["persantage" => $persentage, 'parsed' => $parsed];
        }
    }

    public function actionPlacements(){
        $categories_amount = (new Query())
            ->select('id')
            ->from('parsing_categories_raw')
            ->count();
        return $this->render('fix-placements', ['categories_amount' => $categories_amount]);
    }

    public function actionFixPlacementsParsing(){
        if(Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if (!isset($post['limit']) OR !isset($post['amount'])) {
                Yii::$app->response->statusCode = 500;
                return ['error' => "Parameters missed", "post" => $post];
            } else {
                $categories = ParsingCategoryRaw::find()->offset($post['parsed'])->limit($post['limit'])->orderBy('COL23 ASC')->all();
                $parsed = $post['parsed'];
                foreach($categories as $c){
                    $placements_arr = $this->getPlacementSeo($c);
                    $current_category = ParsingCategory::find()->where(['excel_id' => $c->COL23])->one();
                    foreach($placements_arr as $pl_id => $a) {
                            $cat_placement = new ParsingCategoryPlacement();
                            $cat_placement->categories_id = $current_category->id;
                            $cat_placement->placements_id = $pl_id;
                            $cat_placement->save();
                            $cpt = new ParsingCategoryPlacementText();
                            $cpt->categories_id = $current_category->id;
                            $cpt->placements_id = $pl_id;
                            $cpt->category_placement_id = $cat_placement->id;
                            $cpt->languages_id = 1;
                            $cpt->seo_title = $a['Title'];
                            $cpt->seo_h1 = $a['H1'];
                            $cpt->seo_h2 = $a['H2'];
                            $cpt->name = $c->COL11;
                            $cpt->seo_text = $a['TEXT'];
                            $cpt->seo_desc = $a['Description'];
                            $cpt->seo_keywords = $a['Keywords'];
                            $cpt->save();
                    }
                    $parsed++;
                }
            }
            $persantage = $parsed * 100/$post['amount'];
            $persentage = floor($persantage);

            Yii::$app->response->statusCode = 200;
            return ["persantage" => $persentage, 'parsed' => $parsed];
        }
    }

    public function actionSource()
    {
        $categories_amount = (new Query())
            ->select('id')
            ->from('parsing_categories_raw')
            ->count();
        return $this->render('source', ['categories_amount' => $categories_amount]);
    }

    private function getPlacementSeo(ParsingCategoryRaw $c){
        $seo_raws = ParsingSeoRaw::find()->where(['COL1'=>$c->COL1])->all();
        $seo_arr = [
           // 1 => null, 2 => null, 3 => null, 4 => null, 9 =>null, 10 => null, 11 => null, 12 =>null
        ];
        foreach ($seo_raws as $k => $raw){
            if($raw->COL2 == 2){
                $seo_arr[2][$raw->COL4] = $raw->COL6;
            }
            if($raw->COL2 == 3){
                $seo_arr[1][$raw->COL4] = $raw->COL6;
            }
            if($raw->COL2 == 4){
                $seo_arr[9][$raw->COL4] = $raw->COL6;
            }
            if($raw->COL2 == 5){
                $seo_arr[3][$raw->COL4] = $raw->COL6;
            }
            if($raw->COL2 == 6){
                $seo_arr[4][$raw->COL4] = $raw->COL6;
            }
            if($raw->COL2 == 7){
                $seo_arr[12][$raw->COL4] = $raw->COL6;
            }
            if($raw->COL2 == 8){
                $seo_arr[10][$raw->COL4] = $raw->COL6;
            }
            if($raw->COL2 == 9){
                $seo_arr[11][$raw->COL4] = $raw->COL6;
            }
        }
        return $seo_arr;
    }

    private function getParentColumn(ParsingCategoryRaw $c, array $arr){
        if($c->COL2 != ""){
            return null;
        }else{
            $col_num = str_replace("COL", "",$arr['column']);
            $target_col_num = $col_num - 1;
            if($target_col_num < 2){
                throw new HttpException(500, "Something wrong with columns. ".$c->COL23);
            }
                $parent_cat = ParsingCategoryRaw::find()->where('COL23 < '.$c->COL23)->andWhere('COL'.$target_col_num.' <> ""')->orderBy('COL23 DESC')->one();
            if($parent_cat){
                return $parent_cat;
            }else{
                throw new HttpException(500, "Parent column not found. ".$c->COL23);
            }
        }
    }

    private function makeSeoArray(ParsingCategoryRaw $c, $placement = 1){
        $seoraws = ParsingSeoRaw::find()->where(['COL1' => $c->COL1, 'COL2' => $placement])->all();
        $array = [
            'Title'=>null,
            'H1'=>null,
            'H2'=>null,
            'TEXT'=>null,
            'Description'=>null,
            'Keywords'=>null,
            'Url'=>null,
        ];
        foreach($seoraws as $raw){
            $array[$raw->COL4] = $raw->COL6;
        }
//        foreach($array as $k => $i){
//            if(!$i){
//                throw new HttpException(500, $k." not found in element ".$c->COL23);
//            }
//        }
        return $array;
    }

    private function getCategoryName(ParsingCategoryRaw $c){
        $array = ['category_name' => null, 'column' => null];
        if($c->COL2 != ''){
            $array['category_name'] = $c->COL2;
            $array['column'] = 'COL2';
        }else{
            if($c->COL3 != ''){
                $array['category_name'] = $c->COL3;
                $array['column'] = 'COL3';
            }else{
                if($c->COL4 != ''){
                    $array['category_name'] = $c->COL4;
                    $array['column'] = 'COL4';
                }else{
                    if($c->COL5 != ''){
                        $array['category_name'] = $c->COL5;
                        $array['column'] = 'COL5';
                    }else{
                        if($c->COL6 != ''){
                            $array['category_name'] = $c->COL6;
                            $array['column'] = 'COL6';
                        }else{
                            if($c->COL7 != ''){
                                $array['category_name'] = $c->COL7;
                                $array['column'] = 'COL7';
                            }else{
                                if($c->COL8 != ''){
                                    $array['category_name'] = $c->COL8;
                                    $array['column'] = 'COL8';
                                }else{
                                    if($c->COL9 != ''){
                                        $array['category_name'] = $c->COL9;
                                        $array['column'] = 'COL9';
                                    }else{
                                        if($c->COL10 != '') {
                                            $array['category_name'] = $c->COL10;
                                            $array['column'] = 'COL10';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $array;


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