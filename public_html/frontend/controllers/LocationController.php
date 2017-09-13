<?php
namespace frontend\controllers;

use common\models\Region;
use Yii;
use yii\helpers\Url;


class LocationController extends BaseController
{
    /**
     * Текущая локация
     */
    protected $location = null;

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


    public function actionVyborGoroda(){
        $regions = Region::find()->all();

        $this->setPageTitle(__('_Location'));
        Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs([['label' => __('_Location'), 'link' => Url::toRoute('vybor-goroda')]]);
        Yii::$app->view->params['h1'] = __('_Location');
        return $this->render('list',  [
            'regions'      => $regions,
        ]);
    }

    public function actionSelectLocation(){

    }
}