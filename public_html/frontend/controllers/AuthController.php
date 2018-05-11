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
use common\models\Mailer as Mailer;

class AuthController extends Controller
{

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

        if (Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post(),'');
            if ($model->login()) {

                return $this->goBack();

            } elseif(!$model->validate()) {
                $errors = $model->getErrors();
                foreach($errors as $key => $item){
                    \Yii::$app->getSession()->setFlash($key.'_error', $item[0]);
                }
                \Yii::$app->getSession()->setFlash('model', $model);
                return $this->redirect('/login/');
            }
        } else {
            return $this->render('login');
        }
    }

    public function actionRegistration()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new RegistrForm();
        if (Yii::$app->request->isPost){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post(),'');
            if(!$model->validate()) {
                $errors = $model->getErrors();
                foreach($errors as $key => $item){
                    \Yii::$app->getSession()->setFlash($key.'_error', $item[0]);
                }
                \Yii::$app->getSession()->setFlash('model', $model);
                return $this->redirect('/registration/');
            }else{
                $user = new User();
                $user->email = $model->email;
                $user->last_name = $model->last_name;
                $user->first_name = $model->first_name;
                $user->cities_id = $model->cities_id;
                $user->phone_number = $model->phone_number;
                $user->created_at = time();
                $user->setPassword($model->password);
                $user->save();
                \Yii::$app->getSession()->setFlash('message', __('Successfully registered. Please sign in using your email and password'));
                return $this->redirect('/registration/');
            }
        } else {
            return $this->render('registration');
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
                $errors = $model->getErrors();
                if(isset($errors['email']) && !empty($errors['email'])){
                    \Yii::$app->getSession()->setFlash('recovery_error', $errors['email']);
                }
                \Yii::$app->getSession()->setFlash('model', $model);
                return $this->redirect('/recovery/');
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
                Mailer::send($user->email, 'Восстановление пароля', 'pass-recovery', ['user' => $user, 'token' => $pass_recovery->hash]);
                \Yii::$app->getSession()->setFlash('message', 'На указанный адрес выслано письмо с дальнейшими инструкциями');
                return $this->redirect('/recovery/');
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
                    return $this->redirect('/reset/');
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
                return $this->redirect('/reset/');
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
