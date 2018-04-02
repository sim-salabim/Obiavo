<?php
namespace backend\controllers;

use common\models\libraries\AutopostingFb;
use common\models\libraries\AutopostingInstagram;
use common\models\libraries\AutopostingTwitter;
use common\models\libraries\AutopostingVk;
use common\models\libraries\AutopostingOk;
use common\models\Mailer;
use common\models\SocialNetworks;
use yii\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;

class AutopostingApiController extends Controller
{

    private $_token = 'XC5Vs2iI69OznxOUjIiC';

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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $this->checkCredentials();
        $social_networks = SocialNetworks::find()->where(['autoposting' => 1])->all();
        if(count($social_networks)){
            foreach ($social_networks as $sn){
                $task = $sn->getActiveAutopostingTask();
                if($task){
                    switch ($sn->name){
                        case SocialNetworks::VK_COM :
                            (new AutopostingVk($task))->post();
                            break;
                        case SocialNetworks::FB_COM :
                            (new AutopostingFb($task))->post();
                            break;
                        case SocialNetworks::TWITTER :
                            (new AutopostingTwitter($task))->post();
                            break;
                        case SocialNetworks::INSTAGRAM :
                            (new AutopostingInstagram($task))->post();
                            break;
                        case SocialNetworks::OK_RU :
                            (new AutopostingOk($task))->post();
                            break;
                    }
                }
            }
        }
        Yii::$app->response->statusCode = 200;
        return;
    }

    /**
     * Проверяем предоставленный токен если кто-то вдруг решит постучаться по этому урлу без разрешения
     *
     * @throws ForbiddenHttpException
     */
    private function checkCredentials(){
        $token = Yii::$app->request->get('token');
        if(!$token OR $token != $this->_token){
            $error = (object) array();
            $error->error_code = '403';
            $error->error_msg = 'Неверный токен';
            Mailer::send(\Yii::$app->params['debugEmail'], "Попытка несанкцианированного доступа", 'api-error', ['error' => $error, 'request' => Yii::$app->request->absoluteUrl, 'message' => 'Попытка несанкцианированного доступа']);
            throw new ForbiddenHttpException();
        }
    }
}