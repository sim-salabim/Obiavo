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
        $placements = Placement::find()->all();
        
        return $this->render('index',  compact('placements'));
    }
    
    public function actionCreate()
    {
        $placement = new Placement;     

        $toUrl = Url::toRoute('save');

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
}