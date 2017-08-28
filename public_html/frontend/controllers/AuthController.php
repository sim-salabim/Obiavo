<?php
namespace frontend\controllers;

use common\models\PasswordRecovery as PasswordRecovery;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\RegistrForm;
use frontend\models\PasswordRecoveryForm;
use frontend\models\PasswordResetForm as PasswordResetForm;
use frontend\models\SignupForm;
use common\helpers\JsonData;
use common\models\User as User;

class AuthController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'logout' => ['post'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->load(Yii::$app->request->post(),'') && $model->login()) {

                return $this->goBack();

            } elseif(!$model->validate()) {
                return \common\helpers\JsonData::current([
                    JsonData::SHOW_VALIDATION_ERRORS_INPUT => $model->getErrors()
                ]);
            }
        } else {

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegistration()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegistrForm();

        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->load(Yii::$app->request->post(),'') && $model->login()) {

                return $this->goBack();

            } elseif(!$model->validate()) {
                return \common\helpers\JsonData::current([
                    JsonData::SHOW_VALIDATION_ERRORS_INPUT => $model->getErrors()
                ]);
            }
        } else {

            return $this->render('registration', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRecovery(){
        $model = new PasswordRecoveryForm();
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post(),'');
            if ( !$model->validate()) {
                return \common\helpers\JsonData::current([
                    JsonData::SHOW_VALIDATION_ERRORS_INPUT => $model->getErrors()
                ]);
            }else{
                $user = User::findOne(['email' => Yii::$app->request->post('email')]);
                $pass_recovery = PasswordRecovery::findOne(['users_id' => $user->id, 'recovered' => PasswordRecovery::NOT_RECOVERED]);
                if(!$pass_recovery){
                    $pass_recovery = new PasswordRecovery();
                    $pass_recovery->created_at = time();
                    $pass_recovery->users_id = $user->id;
                }
                $pass_recovery->hash = base64_encode("email=".Yii::$app->request->post('email')."&time=".time());
                $pass_recovery->updated_at = time();
                $pass_recovery->save();
                Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                        ['user' => $user]
                    )
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                    ->setTo($user->email)
                    ->setSubject('Восстановление пароля')
                    ->send();
                \Yii::$app->getSession()->setFlash('message', 'На указанный адрес выслано письмо с дальнейшими инструкциями');
                return $this->redirect('recovery');
            }
        }

        return $this->render('password-recovery-form');

    }

    public function actionReset(){
        $model = new PasswordResetForm();
            if (Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post(),'');
            if ( !$model->validate()) {
                $errors = $model->getErrors();
                if(isset($errors['pass']) && !empty($errors['pass'])){
                    \Yii::$app->getSession()->setFlash('pass_error', $errors['pass']);
                }
                if(isset($errors['pass_confirm']) && !empty($errors['pass_confirm'])){
                    \Yii::$app->getSession()->setFlash('pass_confirm_error', $errors['pass_confirm']);
                }

                return $this->redirect('reset?key='.Yii::$app->request->post('key'));
            }else{

                $pass_recovery = PasswordRecovery::findOne(['hash' => Yii::$app->request->post('key'), 'recovered' => PasswordRecovery::NOT_RECOVERED]);
                if(!$pass_recovery){
                    \Yii::$app->getSession()->setFlash('error', 'Пожалуйста запросите восстановление пароля еще раз или обратитесь к администраторам');
                    return $this->redirect('reset');
                }
                $pass_recovery->recovered = PasswordRecovery::RECOVERED;
                $pass_recovery->updated_at = time();
                $pass_recovery->save();
                $key = Yii::$app->request->post('key');
                $decoded = base64_decode($key);
                $arr = explode('&', $decoded);
                $email = str_replace('email=', '',$arr);
                $user = User::findByEmail($email);
                $user->setPassword(Yii::$app->request->post('pass'));
                $user->save();
                \Yii::$app->getSession()->setFlash('message', 'Пароль успешно обновлен, авторизуйтесь.');
                return $this->redirect('reset');
            }
        }else{
            $key = Yii::$app->request->get('key');

            $key_valid = false;
            if($key && PasswordRecovery::findOne(['hash' => $key, 'recovered' => PasswordRecovery::NOT_RECOVERED])){
                $key_valid = true;
            }
            return $this->render('password-reset-form',
                ['key_valid' => $key_valid,
                'key' => $key]);
        }
    }
}
