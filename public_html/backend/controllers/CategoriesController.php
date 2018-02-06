<?php
namespace backend\controllers;

use common\models\CategoryPlacement;
use common\models\CategoryPlacementText;
use common\models\SocialNetworksGroups;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Category;
use common\helpers\JsonData;
use yii\helpers\Url;

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

            $categories = $categoryParent->getChildren()->all();
        }

        return $this->render('index',compact('categoryParent','categories'));
    }

    public function actionUpdate($id) {

        $category = Category::findOne($id);
        $text = $category->categoriesText;

        $categoriesText = $text ? $text : new \common\models\CategoriesText;

        $toUrl = Url::toRoute(['save','id' => $category->id]);

        return $this->renderAjax('form',compact('category','categoriesText', 'toUrl'));
    }

    public function actionCreate($parent_id = null) {
        $category = new Category();
        $category->parent_id = $parent_id;
        $categoriesText = new \common\models\CategoriesText();

        $toUrl = Url::toRoute(['save','parentID' => $parent_id]);

        return $this->renderAjax('form',  compact('category','categoriesText','toUrl'));
    }

    public function actionSave($id = null, $parentID = null) {
        $post = Yii::$app->request->post();

        if ($id){
            $category = Category::findOne($id);
        } else {
            $category = new Category;
            $category->parent_id = $parentID;
        }

        if (!$category->saveWithRelation($post)){
            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => $category->getErrors(),
            ]);
        }

        if (!empty($post['placements'])){
            $category->setPlacements($post['placements']);
        }
        (new Query())
            ->createCommand()
            ->delete('social_networks_groups_main_categories', ['categories_id' => $category->id])
            ->execute();

        if(isset($post['Category']['social_networks_groups_main_id'])){
            (new Query)
                ->createCommand()
                ->insert('social_networks_groups_main_categories', ['categories_id' => $category->id, 'main_group_id' => $post['Category']['social_networks_groups_main_id']])
                ->execute();
        }

        return $this->sendJsonData([
                JsonData::SUCCESSMESSAGE => "\"{$category->techname}\" успешно сохранено",
                JsonData::REFRESHPAGE => '',
        ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionUpdateSeoAttached($id){
        $categoryPlacementsTexts = CategoryPlacementText::find()->where(['in','category_placement_id', 'SELECT `id` FROM `categories_has_placements`  WHERE `categories_id`='.$id])->all();
        $categories_placements = CategoryPlacement::find()->where(['categories_id'=>$id])->all();
        $toUrl = Url::toRoute(['save-seo-attached']);

        return $this->renderAjax('form-seo-attached',  compact('categoryPlacementsTexts', 'id', 'toUrl', 'categories_placements'));

    }

    public function actionSaveSeoAttached(){
        $post = Yii::$app->request->post();
        unset($post['json']);
        $array = [];
        foreach($post as $key => $row){
            foreach($row as $k => $v){
                $array[$k][$key] = $v;
            }
        }
        foreach($array as $key => $values){

                $categoryPlacementText = CategoryPlacementText::find()->where(['category_placement_id' => $key])->one();
            if(!$categoryPlacementText){
                $categoryPlacementText = new CategoryPlacementText();
                $categoryPlacementText->category_placement_id = $key;
            }
            foreach ($values as $k => $v){
                $categoryPlacementText->{$k} = $v;
            }
            if(!$categoryPlacementText->save()){
                return $this->sendJsonData([
                    JsonData::SHOW_VALIDATION_ERRORS_INPUT => $categoryPlacementText->getErrors(),
                ]);
            }
        }
        return $this->sendJsonData([
            JsonData::SUCCESSMESSAGE => "Данные успешно сохранены",
            JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $category = Category::findOne($id);
        $category->delete();

        return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "\"{$category->techname}\" успешно удалено",
                    JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionSaveLang($id,$languages_id){
        $category = Category::find()
                        ->where(['id' => $id])
                        ->withText($languages_id)
                        ->one();

        if ($this->isJson()){
            $text = $category->_mttext;
            $text->categories_id = $category->id;
            $text->languages_id = $languages_id;
            $text->load(Yii::$app->request->post());

            if ($text->save()){
                return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "\"{$category->techname}\" успешно сохранено",
                    JsonData::REFRESHPAGE => '',
                ]);
            }

            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => \yii\widgets\ActiveForm::validate($text),
            ]);
        }

        return $this->render('savelang',[
            'category' => $category,
        ]);
    }

    /** Используется для инпута с автокомплитом
     *
     * @return array
     */
    public function actionSearch(){
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = $post['query'];
        $categories = Category::find()
            ->leftJoin('categories_text', 'categories_text.categories_id = categories.id')
            ->where("categories_text.name LIKE '".$query."%'")
            ->all();
        $result = [];
        foreach($categories as $category){
            $result[$category->id] = array('id' => $category->id, 'text' => $category->_text->name);
        }
        return $result;
    }

    public function actionSearchForMainGroups(){
        $post = Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = $post['query'];
        $andWhere = '';
        $selected_ids = (new Query())->select('categories_id as id')->from('social_networks_groups_main_categories')->all();
        $not_in_str = "(";
        if(count($selected_ids)){
             foreach ($selected_ids as $k => $v){
                 $not_in_str .= $v['id'];
                 $nextIdx = $k + 1;
                 if(isset($selected_ids[$nextIdx]) OR (isset($post['excludedIds']) and $post['excludedIds'] != '')){
                     $not_in_str .= ",";
                 }
             }
        }
        if(isset($post['excludedIds']) and $post['excludedIds'] != ''){
            $not_in_str .= $post['excludedIds'];
        }
        $not_in_str .= ')';
        if($not_in_str != '()') {
            $andWhere .= 'categories.id NOT IN ' . $not_in_str;
        }
        $categories = Category::find()
            ->leftJoin('categories_text', 'categories_text.categories_id = categories.id')
            ->where("categories_text.name LIKE '".$query."%'")
            ->andWhere($andWhere)
            ->all();
        $result = [];
        foreach($categories as $category){
            $result[$category->id] = array('id' => $category->id, 'text' => $category->_text->name);
        }
        return $result;
    }
}