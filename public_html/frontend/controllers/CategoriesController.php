<?php
namespace frontend\controllers;

use common\models\Ads;
use common\models\libraries\AdsSearch;
use common\models\Placement;
use common\models\PlacementsText;
use Yii;
use common\models\Category;
use yii\web\HttpException;
use common\models\Language;


class CategoriesController extends BaseController
{
    /**
     * Текущая категория
     */
    protected $category = null;
    protected $placement = null;

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
        $action = Yii::$app->request->get('placement');
        $action_id = null;
        if($action){
            $action_id = PlacementsText::findOne(['url' => $action])->placements_id;
        }

        /**
         * В данном месте проверку можно убрать, т.к. она осуществляется в правиле для роута
         */
        $category = Category::getByOldUrlCache(($categoryUrl) ?: null);

        if (!$category) {
            throw new HttpException(404, 'Not Found');
        }

        $this->category = $category;

        $subCategories = $this->category->children;
        $categoryPlacements = $this->category->placements;

        $this->setPageTitle($this->category);
        $breadcrumbs = $this->category->getAllParentsForBreadcrumbs();
        Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs($breadcrumbs);
        Yii::$app->view->params['h1'] = $this->category->_text->name;
        Yii::$app->view->params['seo_text'] = $this->category->_text->seo_text;
        $librarySearch = new AdsSearch();
        $librarySearch->setCategory($this->category->id);
        $librarySearch->setAction($action_id);
        return $this->render('index',  [
            'category'      => $this->category,
            'categories'    => $subCategories,
            'placements'    => $categoryPlacements,
            'current_action'=> $action,
            'ads'           => (new Ads())->getList($librarySearch)
        ]);
    }

    public function actionGetSubCategories(){
        $subcats_json = '[';
        if (Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            $subcategories = Category::find()
                ->withText(['laguages_id' => Language::getDefault()->id])
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
                ->withText(['laguages_id' => Language::getDefault()->id])
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