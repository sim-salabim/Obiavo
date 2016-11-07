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

        if (! $id) {
            $categories = Category::getMainCategories();
        } else {
            $categories = Category::find()
                                ->where(['parent_id' => $id])->all();
        }

        return $this->render('index',[
            'categories' => $categories
        ]);
    }

    public function actionGetChildrenCategories($id) {
        $childCategories = Category::find()
                            ->where(['parent_id' => $id])->all();

        if (\Yii::$app->request->getQueryParam('onlycontent',false)){
            return $this->renderAjax('index',[
                        'categories' => $childCategories
                    ]);
        };

        return $this->render('index',[
            'categories' => $childCategories
        ]);
    }

    public function actionEditCategory($id) {
        $category = Category::findOne($id);

        return $this->renderAjax('edit',[
            'category' => $category
        ]);
    }

    public function actionAppendCategory() {
        $category = new Category();
        $categoryGenerate = new \common\models\CategoryGenerate;

        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

        return $this->renderAjax('append',  compact('category','categoryGenerate'));
    }

    public function actionSaveCategory($id = null) {
        $postData = Yii::$app->request->post();

        $category = new Category();
        $categoryGenerate = new \common\models\CategoryGenerate;

        $category->load($postData);
        $categoryGenerate->load($postData);

        if ($category->save()){
            $category->setCategoryGeneratedRecord($categoryGenerate);
        }


        $category->save();

        $this->sendJsonData([
            JsonData::REFRESHPAGE => ''
        ]);

        return $this->getJsonData();
    }
}