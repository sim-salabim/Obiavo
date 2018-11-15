<?php
namespace frontend\controllers;

use common\models\Ads;
use common\models\Category;
use common\models\CategoryPlacement;
use common\models\City;
use common\models\Language;
use common\models\libraries\AdsSearch;
use common\models\PlacementsText;
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
        $action = Yii::$app->request->get('placement');
        if($city != LocationHelper::getCurrentDomain()){
            $domain = City::setCookieLocation($city);
            $redirect_str = $domain ? $domain : "";
            $redirect_str .= "/".$categoryUrl."/";
            return $this->redirect(Url::toRoute($redirect_str));
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
            ->all();
//        $categoryPlacements = $this->category->placements;

        if($action){
            $category_placement = CategoryPlacement::find()->where(['placements_id' => $action_id, 'categories_id' => $category->id])->one();
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
        $breadcrumbs = $this->category->getAllParentsForBreadcrumbs();
        Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs($breadcrumbs);
        $librarySearch = new AdsSearch();
        $librarySearch->setMainCategory($this->category->id);
        $librarySearch->setAction($action_id);
        $librarySearch->setActive(true);
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
        $this->setSeo($this->seo_h1, $this->seo_h2, $this->seo_text, $this->seo_desc, $this->seo_keywords, $this->canonical);
        if($page > 1){
            $this->seo_title = $this->seo_title." - ".__('Page')." ".$page;
        }
        $this->setPageTitle($this->seo_title);
        $this->setNextAndPrevious($ads_list, $librarySearch, $page);
        return $this->render('index',  [
            'current_category'      => $this->category,
            'categories'    => $subCategories,
            'row_list'      => true,
//            'placements'    => $categoryPlacements,
            'current_action'=> $action,
            'page'          => $page,
            'ads_search'    => $ads_list,
            'library_search'=> $librarySearch
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
            ->where(['parent_id' => $id])
            ->orderBy('order ASC, brand ASC, techname ASC')
            ->withText(['languages_id' => Language::getDefault()->id])
            ->all();
        $out = "[";
        foreach($categories as $k => $row){
            $kid = Category::find()->where(['parent_id' => $row['id']])->one();
            $has_kids = $kid ? 'true' : 'false';
            $out .= '{"key": "'.$row['id'].'","isLazy":'.$has_kids.',"isFolder":'.$has_kids.',"title": "'.$row['techname'].'"}';
            $next = $k + 1;
            if(isset($categories[$next])){
                $out .= ",";
            }
        }
        return $out."]";
    }
}