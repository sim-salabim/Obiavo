<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Category;
use common\helpers\JsonData;

/**
 * Site controller
 */
class CategoriesController extends BaseController
{
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
    public function actionIndex($id = null)
    {
        $categories = null;
        $categoryParent = new Category;

        if (! $id) {
            $categories = Category::getMainCategories();

        } else {
            $categoryParent = Category::findOne($id);

            $categories = $categoryParent->getChildrens()->all();
        }

        return $this->render('index',compact('categoryParent','categories'));
    }

    public function actionEditCategory($id) {
        $category = Category::findOne($id);

        $categoryGenerate = $category->getCategoryGenerated()->one();

        return $this->renderAjax('edit',compact('category','categoryGenerate'));
    }

    public function actionAppendCategory($id = null) {
        $category = new Category();
        $category->parent_id = $id;
        $categoryGenerate = new \common\models\CategoryGenerate;

        return $this->renderAjax('append',  compact('category','categoryGenerate'));
    }

    public function actionSaveCategory($id = null, $parentID = null) {
        $postData = Yii::$app->request->post();

        if ($id){
            $category = Category::findOne($id);

            $category->saveUpdateData($postData);

        } else {
            $category = new Category();
            $category->parent_id = $parentID;

            $category->saveNewData($postData);
        }

        $this->sendJsonData([
            JsonData::REFRESHPAGE => ''
        ]);

        return $this->getJsonData();
    }
}