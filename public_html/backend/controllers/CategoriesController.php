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

        $parentCategoryId = $id;

        if (! $id) {
            $categories = Category::getMainCategories();
        } else {
            $categories = Category::find()
                                ->where(['parent_id' => $id])->all();
        }

        return $this->render('index',compact('parentCategoryId','categories'));
    }

    public function actionEditCategory($id) {
        $category = Category::findOne($id);

        return $this->renderAjax('edit',[
            'category' => $category
        ]);
    }

    public function actionAppendCategory($id = null) {
        $category = new Category();
        $category->parent_id = $id;
        $categoryGenerate = new \common\models\CategoryGenerate;

        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

        return $this->renderAjax('append',  compact('category','categoryGenerate'));
    }

    public function actionSaveCategory($id = null) {
        $postData = Yii::$app->request->post();

        $category = new Category();
        $category->parent_id = $id;

        $categoryGenerate = new \common\models\CategoryGenerate;

        $category->load($postData);
        $categoryGenerate->load($postData);

        $category->setRelateForCategoryGenerated($categoryGenerate);


        $category->save();

        $this->sendJsonData([
            JsonData::REFRESHPAGE => ''
        ]);

        return $this->getJsonData();
    }
}