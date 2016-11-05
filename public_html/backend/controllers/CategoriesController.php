<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Category;

/**
 * Site controller
 */
class CategoriesController extends Controller
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
    public function actionIndex()
    {
        $mainCategories = Category::getMainCategories();

        if (\Yii::$app->request->getQueryParam('onlycontent',false)){
            return $this->renderAjax('index',[
                        'categories' => $mainCategories
                    ]);
        };

        return $this->render('index',[
            'categories' => $mainCategories
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

        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

        return $this->renderAjax('append',[
            'category' => $category
        ]);
    }

    public function actionSaveCategory($id = null) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
        ['type' => 'refreshpage', 'data' => '']
            ];
    }
}