<?php
namespace frontend\controllers;

use common\models\Ads;
use common\models\CategoryPlacement;
use common\models\CategoryPlacementText;
use common\models\libraries\AdsSearch;
use common\models\Placement;
use common\models\PlacementsText;
use Yii;
use common\models\Category;
use yii\helpers\Url;
use yii\web\HttpException;
use common\models\Language;


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
        $this->canonical = Url::home(true) . $categoryUrl . "/";
        $action = Yii::$app->request->get('placement');
        $sort = Yii::$app->request->get('sort');
        $direction = Yii::$app->request->get('direction');
        $action_id = null;
        if($action){
            $action_id = PlacementsText::findOne(['url' => $action])->placements_id;
            $this->canonical .= $action . '/';
        }

        /**
         * В данном месте проверку можно убрать, т.к. она осуществляется в правиле для роута
         */
        $category = Category::getByOldUrlCache(($categoryUrl) ?: null);

        $this->category = $category;

        $subCategories = $this->category->children;
        $categoryPlacements = $this->category->placements;

        $this->setPageTitle($this->category);
        if($action){
            $category_placement = CategoryPlacement::find()->where(['placements_id' => $action_id, 'categories_id' => $category->id])->one();
        }
        $breadcrumbs = $this->category->getAllParentsForBreadcrumbs();
        Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs($breadcrumbs);
        Yii::$app->view->params['h1'] = (!$action) ? $this->category->_text->seo_h1 : $category_placement->_text->seo_h1;
        Yii::$app->view->params['seo_text'] = (!$action) ? $this->category->_text->seo_text : $category_placement->_text->seo_text;
        Yii::$app->view->params['seo_desc'] = (!$action) ? $this->category->_text->seo_desc : $category_placement->_text->seo_desc;
        Yii::$app->view->params['seo_keywords'] = (!$action) ? $this->category->_text->seo_keywords : $category_placement->_text->seo_keywords;
        Yii::$app->view->params['canonical'] = $this->canonical;
        $librarySearch = new AdsSearch();
        $librarySearch->setCategory($this->category->id);
        $librarySearch->setAction($action_id);
        $loaded = (Yii::$app->request->get('loaded')) ? Yii::$app->request->get('loaded') + $librarySearch->limit : $librarySearch->loaded;
        $librarySearch->setLimit($loaded);
        if($sort AND $direction) {
            $librarySearch->setSorting($sort." ".$direction);
        }
        return $this->render('index',  [
            'current_category'      => $this->category,
            'categories'    => $subCategories,
            'row_list'      => true,
            'placements'    => $categoryPlacements,
            'current_action'=> $action,
            'loaded'        => $loaded,
            'ads_search'    => (new Ads())->getList($librarySearch)
        ]);
    }

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
}