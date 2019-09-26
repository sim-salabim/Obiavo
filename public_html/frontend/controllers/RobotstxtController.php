<?php
namespace frontend\controllers;

use common\models\Category;
use common\models\CategoryPlacement;
use common\models\City;
use common\models\Placement;
use common\models\SitemapIndex;
use frontend\components\Location;
use yii\web\HttpException;
use Yii;


class RobotstxtController extends BaseController
{
    public $layout=false;
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $domain = Location::getCurrentDomain();
        switch($domain){
            case 'obiavo.ru':
                echo file_get_contents('../views/robotstxt/obiavo_ru.txt');
                break;
            case 'obiavo.by':
                echo file_get_contents('../views/robotstxt/obiavo_by.txt');
                break;
            case 'obiavo.uz':
                echo file_get_contents('../views/robotstxt/obiavo_uz.txt');
                break;
            case 'obiavo.su':
                echo file_get_contents('../views/robotstxt/obiavo_su.txt');
                break;
            case 'obiavo.kz':
                echo file_get_contents('../views/robotstxt/obiavo_kz.txt');
                break;
            case 'obiavo.com':
                echo file_get_contents('../views/robotstxt/obiavo_com.txt');
                break;
            case 'obiavo.com.ng':
                echo file_get_contents('../views/robotstxt/obiavo_com_ng.txt');
                break;
            case 'obiavo.in':
                echo file_get_contents('../views/robotstxt/obiavo_in.txt');
                break;
            case 'obiavo.net':
                echo file_get_contents('../views/robotstxt/obiavo_net.txt');
                break;
            case 'obiavo.co.uk':
                echo file_get_contents('../views/robotstxt/obiavo_co_uk.txt');
                break;
        }

    }
}
