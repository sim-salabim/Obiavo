<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use common\models\Category;
use yii\web\HttpException;


class CategoriesController extends BaseController
{
    /**
     * Текущая категория
     */
    protected $category = null;

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

        /**
         * В данном месте проверку можно убрать, т.к. она осуществляется в правиле для роута
         */
        $category = Category::getByOldUrlCache(($categoryUrl) ?: null);

        if (!$category) {
            throw new HttpException(404, 'Not Found');
        }

        $this->category = $category;

        $subCategories = $this->category->childrens;
        $categoryPlacements = $this->category->placements;

        $this->setPageTitle($this->category);
        $breadcrumbs = $this->category->getAllParentsForBreadcrumbs();
        Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs($breadcrumbs);
        return $this->render('index',  [
            'category'      => $this->category,
            'categories'    => $subCategories,
            'placements'    => $categoryPlacements
        ]);
    }
}