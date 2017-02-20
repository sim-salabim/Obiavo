<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Placement;
use common\helpers\JsonData;
use yii\helpers\Url;

/**
 * Site controller
 */
class PlacementsController extends BaseController
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

    public function actionIndex(){
        $placements = Placement::find()->withText()->all();

        return $this->render('index',  compact('placements'));
    }

    public function actionCreate()
    {
        $placement = new Placement;

        $toUrl = Url::toRoute('save');

        return $this->render('create',  compact('placement','toUrl'));
    }

    public function actionUpdate($id)
    {
       $placement = Placement::findOne($id);

       $toUrl = Url::toRoute(['save','id' => $id]);

        return $this->render('create',  compact('placement','toUrl'));
    }

    public function actionSave($id = null)
    {
        $placement = ($id) ? Placement::findOne($id) : new Placement;

        $placement->loadWithRelation(['placementsText'],Yii::$app->request->post());
        $placement->save();

        return $this->sendJsonData([
                JsonData::SUCCESSMESSAGE => "Тип \"{$placement->_text->name}\" успешно сохранен",
                JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionDelete($id){

        $placement = Placement::findOne($id);
        $text = $placement->_text;

        $placement->delete();

        return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "Тип \"{$text->name}\" успешно удален",
                    JsonData::REFRESHPAGE => '',
        ]);
    }

    public function actionSaveLang($id,$languages_id){

        $placement = Placement::find()
                        ->where(['id' => $id])
                        ->withText($languages_id)
                        ->one();

        if ($this->isJson()){
            $text = $placement->_mttext;
            $text->placements_id = $placement->id;
            $text->languages_id = $languages_id;
            $text->load(Yii::$app->request->post());

            if ($text->save()){
                return $this->sendJsonData([
                    JsonData::SUCCESSMESSAGE => "\"{$text->name}\" успешно сохранено",
                    JsonData::REFRESHPAGE => '',
                ]);
            }

            return $this->sendJsonData([
                JsonData::SHOW_VALIDATION_ERRORS_INPUT => \yii\widgets\ActiveForm::validate($text),
            ]);
        }

        return $this->render('savelang',[
            'placement' => $placement
        ]);
    }
}