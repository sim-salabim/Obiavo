<?php
namespace frontend\controllers;

use common\models\Ads;
use common\models\Category;
use common\models\CategoryPlacement;
use common\models\City;
use common\models\Language;
use common\models\libraries\AdsSearch;
use common\models\PlacementsText;
use common\models\Region;
use frontend\helpers\LocationHelper;
use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\HttpException;

class CategoriesController extends BaseController
{
    /**
     * Текущая категория
     */
    protected $category = null;
    protected $placement = null;
    protected $canonical = null;
    public $params;

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

    public function actionIndex(){
        $categoryUrl = Yii::$app->request->get('category');
        $city = Yii::$app->request->get('city') ?: null;
        $root_url = "/";
        if($city){
            $city_found = City::find()->where(['domain' => $city])->one();
            if($city_found and $city_found->region->country->id != Yii::$app->location->country->id){
                throw new HttpException(404, 'Not Found');
            }
            $region_found = Region::find()->where(['domain' => $city])->one();
            if($region_found and $region_found->country->id != Yii::$app->location->country->id){
                throw new HttpException(404, 'Not Found');
            }
            $root_url = $root_url.$city."/";
        }
        $place = Yii::$app->location->country;
        $librarySearch = new AdsSearch();
        $page = (Yii::$app->request->get('page')) ? Yii::$app->request->get('page') : $librarySearch->page;
        if(Yii::$app->location->city){
            $place = Yii::$app->location->city;
        }
        if(Yii::$app->location->region and !$place){
            $place = Yii::$app->location->region;
        }
        $root_url = $root_url.$categoryUrl."/";
        $action = Yii::$app->request->get('placement');
        if($city != LocationHelper::getCurrentDomain()){
            City::setCookieLocation($city);
        }
        if($city){
            $this->setUrlForLogo($city);
        }
        $city_canonical = '';
        if($city){
            $city_canonical =  Yii::$app->request->get('city'). '/';
        }
        $this->canonical = Url::home(true) . $city_canonical .$categoryUrl . "/";
        $sort = Yii::$app->request->get('sort');
        $direction = Yii::$app->request->get('direction');
        $this->checkGetParams();
        $action_id = null;
        if($action){
            $action_id = PlacementsText::findOne(['url' => $action])->placements_id;
            $this->canonical .= $action . '/';
        }

        $category = Category::getByUrl($categoryUrl);
        if(!$category->active){
            throw new HttpException(404, 'Not Found');
        }
        $this->category = $category;

        $subCategories = Category::find()
            ->where(['parent_id' => $this->category->id, 'active' => 1])
            ->orderBy('order ASC, brand ASC, techname ASC')
            ->withText(Language::getId())
            ->all();
//        $categoryPlacements = $this->category->placements;

        if($action){
            $category_placement = CategoryPlacement::find()
                ->where(['placements_id' => $action_id, 'categories_id' => $category->id])
                ->withText(Language::getId())
                ->one();
            $this->seo_title = $category_placement->_text->seo_title;
            $this->seo_h1 = $category_placement->_text->seo_h1;
            $this->seo_h2 = $category_placement->_text->seo_h2;
            $this->seo_text = $category_placement->_text->seo_text;
            $this->seo_desc = $category_placement->_text->seo_desc;
            $this->seo_keywords = $category_placement->_text->seo_keywords;
        }else{
            $this->seo_title = $category->_text->seo_title;
            $this->seo_h1 = $category->_text->seo_h1;
            $this->seo_h2 = $category->_text->seo_h2;
            $this->seo_text = $category->_text->seo_text;
            $this->seo_desc = $category->_text->seo_desc;
            $this->seo_keywords = $category->_text->seo_keywords;
        }
        if($page > 1){
            $this->seo_desc .= " - ".__('Page')." $page";
        }
        //если мы не на первой странице списка, то уберем сео-текст
        if($page != 1 or $sort or $direction){
            $this->seo_text = null;
        }
        //TODO move this request to cache
        $breadcrumbs = $this->category->getAllParentsForBreadcrumbs($place);
        $breadcrumbs[] = [
            'label' => __('Pavilion')." ".__('in')." ".$place->_text->name_rp,
            'link' => $place->domain. "/",
            'use_cookie' => true,
            'is_active' => false,
        ];
        Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs($breadcrumbs, true);
        $librarySearch->setMainCategory($this->category->id);
        $librarySearch->setAction($action_id);
        $librarySearch->setActive(true);
        $librarySearch->setAll(true);
        $page = (Yii::$app->request->get('page')) ? Yii::$app->request->get('page') : $librarySearch->page;
        $librarySearch->setPage($page);
        if($sort AND $direction) {
            $librarySearch->setSorting($sort." ".$direction);
        }

        $ads_list = Ads::getList($librarySearch);
        if($page > 1 AND !count($ads_list['items'])){
            $page = ceil(($ads_list['count'] / $librarySearch->limit));
            $librarySearch->page = $page;
            $ads_list = Ads::getList($librarySearch);
        }
        $this->switchSeoKeys($ads_list);
        if($page > 1){
            $this->seo_title = $this->seo_title." - ".__('Page')." ".$page;
        }
        if($sort or $direction){
            $seo_sort_str = " - ".__('Sorting')." ";
            switch($sort){
                case 'title':
                    $seo_sort_str .= __('by alphabet')." ";
                    break;
                case 'price':
                    $seo_sort_str .= __('by price')." ";
                    break;
                case 'created_at':
                    $seo_sort_str .= __('by date')." ";
            }
            switch($direction){
                case 'asc':
                    $seo_sort_str .= __('asc');
                    break;
                case 'desc':
                    $seo_sort_str .= __('desc');
                    break;
            }
            $this->seo_desc .= $seo_sort_str.".";
            $this->seo_title .= $seo_sort_str;
        }
        $this->setSeo($this->seo_h1, $this->seo_h2, $this->seo_text, $this->seo_desc, $this->seo_keywords, $this->canonical);
        $this->setPageTitle($this->seo_title);
        $this->setNextAndPrevious($ads_list, $librarySearch, $page);
        $page_pagination_title = "{page_num:key} ".__('of category').": ".$this->category->_text->name." ".__('in')." ".$place->_text->name_rp;
        return $this->render('index',  [
            'current_category'      => $this->category,
            'categories'    => $subCategories,
            'row_list'      => true,
//            'placements'    => $categoryPlacements,
            'current_action'=> $action,
            'page'          => $page,
            'ads_search'    => $ads_list,
            'library_search'=> $librarySearch,
            'root_url' => $root_url,
            'page_pagination_title' => $page_pagination_title
        ]);
    }

    /** Возвращает дочерние категории для категории $POST['category_id']
     *
     * @return string
     */
    public function actionGetSubCategories(){
        $subcats_json = '[';
        if (Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            $subcategories = Category::find()
                ->withText(['languages_id' => Language::getDefault()->id])
                ->where(['parent_id' => $post['category_id']])
                ->all();
            if(count($subcategories)) {
                foreach ($subcategories as $key => $subcat) {
                    $comma = (isset($subcategories[++$key])) ? ',' : '';
                    $has_chidren = (count($subcat->children)) ? '1' : '0';
                    $subcats_json .= '{"id":"'.$subcat->id.'","name":"'.$subcat->_text->name.'","has_children":'.$has_chidren.'}'.$comma;
                }
            }
        }
        $subcats_json .= ']';
        return $subcats_json;
    }

    /** Возвращает виды обьявлений для категории $POST['category_id']
     *
     * @return string
     */
    public function actionGetCategoryPlacement(){
        $action_json = '[';
        if (Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            $category = Category::find()
                ->withText(['languages_id' => Language::getDefault()->id])
                ->where(['id' => $post['category_id']])
                ->one();
            if(count($category->placements) > 0){
                foreach($category->placements as $key => $action){
                    $comma = (isset($category->placements[++$key])) ? ',' : '';
                    $action_json .= '{"id":"'.$action->id.'","name":"'.$action->_text->name.'"}'.$comma;
                }
            }
        }
        $action_json .= ']';
        return $action_json;
    }

    /** Проверяет корректны ли GET параметры сортировки
     *  если нет выводит 404 Exception
     * @throws HttpException
     */
    private function checkGetParams(){
        $sort = Yii::$app->request->get('sort');
        $direction = Yii::$app->request->get('direction');
        if(($sort AND !$direction) OR ($direction AND !$sort)){
            throw new HttpException(404, 'Not Found');
        }else{
            if($sort){
                if($sort == 'created_at' || $sort == 'price' || $sort == 'title'){
                    true;
                }else{
                    throw new HttpException(404, 'Not Found');
                }
            }
            if($direction) {
                if ($direction == 'desc' || $direction == 'asc') {
                    true;
                } else {
                    throw new HttpException(404, 'Not Found');
                }
            }
        }
    }

    public function actionSearchCategoriesForSelect(){
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];

        if(isset($post['q'])) {
            $query = new Query();
            $query->select([
                'categories.id as id',
                'categories.techname as name',
                'categories.parent_id as parent_id',
                'second_cat.parent_id as second_parent_id',
                'second_cat.techname as second_name',
                'third_cat.parent_id as third_parent_id',
                'third_cat.techname as third_name',
            ])->from('categories')
                ->where("categories.techname LIKE '".$post['q']."%'")
                ->andWhere('categories.parent_id IS NOT NULL')
                ->andWhere('second_cat.parent_id IS NOT NULL')
                ->join('LEFT OUTER JOIN',
                    'categories as second_cat',
                    'categories.parent_id = second_cat.id'
                )
                ->join('LEFT OUTER JOIN',
                    'categories as third_cat',
                    'second_cat.parent_id = third_cat.id'
                );
            $command = $query->createCommand();
            $data = $command->queryAll();
        }
        if(isset($data) and $data){
            foreach($data as $row){
                $out[] = array('id' => $row['id'], 'text' => $row["third_name"]." / ".$row["second_name"]." / ".$row['name'], "name" => $row['name']);
            }
        }
        return $out;
    }


    public function actionGetRootCategories(){
        $post = Yii::$app->request->get();
        $id = ($post['key'] == "#") ? null : $post['key'];
        $categories = Category::find()
            ->where(['parent_id' => $id, 'active'=> 1])
            ->orderBy('order ASC, brand ASC, techname ASC')
            ->withText(['languages_id' => Language::getId()])
            ->all();
        $out = "[";
        $DB = Yii::$app->getDb();
        foreach($categories as $k => $row){
            $next = $k + 1;
            if($row->_text->name != "") {
                $query = $DB->createCommand("SELECT categories.id FROM categories LEFT JOIN  categories_text ON categories_text.categories_id = categories.id WHERE parent_id = ".$row['id']." AND categories_text.languages_id = ".Language::getId()." AND categories_text.name IS NOT NULL AND TRIM(categories_text.name) <> '' AND categories.active = 1");
                $kid = $query->queryOne();
                $has_kids = ($kid) ? 'true' : 'false';
                $out .= '{"key": "' . $row['id'] . '","isLazy":' . $has_kids . ',"isFolder":' . $has_kids . ',"title": "' . $row->_text->name . '"}';

                if(isset($categories[$next])){
                    $out .= ",";
                }
            }
        }
        if(substr($out, -1) == ","){
            $out = substr($out, 0, -1);
        }
        return $out."]";
    }
}