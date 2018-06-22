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
use common\models\ParsingCategoryRaw;
use common\models\ParsingSeoRaw;
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
                    $category = Category::find()->where(["id" => $cat->id]);
                    $category_raw = CategoriesRaw::find()->where(['categories_id' => $cat->id])->one();
                    if(!isset($category->id)){
                        $category = new Category();
                    }
                    $category->id = $cat->id;
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
            $persantage = $parsed * 100/$post['amount'];
            $persentage = floor($persantage);

            Yii::$app->response->statusCode = 200;
            return ["persantage" => $persentage, 'parsed' => $parsed];
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
                    $new_text->seo_h1 = $seo_array['H1'];
                    $new_text->seo_h2 = $seo_array['H2'];
                    $new_text->url = $seo_array['Url'];
                    $new_text->seo_title = $seo_array['Title'];
                    $new_text->seo_desc = $seo_array['Description'];
                    $new_text->seo_keywords = $seo_array['Keywords'];
                    $new_text->seo_text = $seo_array['TEXT'];
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

    public function actionFixPlacements(){
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
                    foreach($placements_arr as $pl_id => $ar) {
                        if($ar) {
                            $cpt = new ParsingCategoryPlacementText();
                            $cat_placement = new ParsingCategoryPlacement();
                            $cat_placement->categories_id = $current_category->id;
                            $cat_placement->placements_id = $pl_id;
                            $cat_placement->save();
                            $cpt->categories_id = $current_category->id;
                            $cpt->placements_id = $pl_id;
                            $cpt->category_placement_id = $cat_placement->id;
                            $cpt->languages_id = 1;
                            $cpt->seo_title = $ar['Title']->COL6;
                            $cpt->seo_h1 = $ar['H1']->COL6;
                            $cpt->seo_h2 = $ar['H2']->COL6;
                            $cpt->name = $c->COL11;
                            $cpt->seo_text = $ar['TEXT']->COL6;
                            $cpt->seo_desc = $ar['Description']->COL6;
                            $cpt->seo_keywords = $ar['Keywords']->COL6;
                            $cpt->save();
                        }
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
        return $this->render('fix-source', ['categories_amount' => $categories_amount]);
    }

    private function getPlacementSeo(ParsingCategoryRaw $c){
        $seo_raws = ParsingSeoRaw::find()->where(['COL1'=>$c->COL1])->andFilterWhere(["in", "COL2", (new \yii\db\Query())->select('id')->from('parsing_placements')])->all();
        $seo_arr = [
            1 => null, 2 => null, 3 => null, 4 => null, 9 =>null, 10 => null, 11 => null, 12 =>null
        ];
        foreach ($seo_raws as $raw){
            if($raw->COL2 == 2){
                $seo_arr[2][$raw->COL4] = $raw;
            }
            if($raw->COL2 == 3){
                $seo_arr[1][$raw->COL4] = $raw;
            }
            if($raw->COL2 == 4){
                $seo_arr[9][$raw->COL4] = $raw;
            }
            if($raw->COL2 == 5){
                $seo_arr[3][$raw->COL4] = $raw;
            }
            if($raw->COL2 == 6){
                $seo_arr[4][$raw->COL4] = $raw;
            }
            if($raw->COL2 == 7){
                $seo_arr[12][$raw->COL4] = $raw;
            }
            if($raw->COL2 == 8){
                $seo_arr[10][$raw->COL4] = $raw;
            }
            if($raw->COL2 == 9){
                $seo_arr[11][$raw->COL4] = $raw;
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