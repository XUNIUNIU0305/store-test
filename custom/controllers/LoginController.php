<?php
namespace custom\controllers;

use Yii;
use common\controllers\Controller;
use custom\models\LoginModel;
use yii\helpers\Url;

class LoginController extends Controller{

    public $layout = 'global';

    protected $access = [
        'index' => [null, 'get'],
        'verify-captcha' => [null, 'post'],
        'login' => ['?', 'post'],
        'logout' => [null, 'get'],
        'verify-identity' => [null, 'post'],
    ];

    public function actionIndex(){
        if(!Yii::$app->user->isGuest){
            $this->redirect(Yii::$app->homeUrl);
        }
        return $this->render('index');
    }

    public function actionVerifyCaptcha(){
        return $this->success(['result' => LoginModel::verifyCaptcha(Yii::$app->request->post('captcha', ''))]);
    }

    public function actionLogin(){
        $loginModel = new LoginModel([
            'scenario' => LoginModel::SCE_LOGIN,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($result = $loginModel->process()){
            if($result === true){
                return $this->success([]);
            }else{
                return $this->success($result);
            }
        }else{
            return $this->failure($loginModel->getErrorCode());
        }
    }

    public function actionLogout(){
        $user = \Yii::$app->user;
        if($user->isGuest || $user->logout()){
            if($redirect = \Yii::$app->request->get('redirect')){
                return $this->redirect($redirect);
            }
            return $this->success([]);
        }else{
            return $this->failure(3021);
        }
    }

    public function actionVerifyIdentity(){
        $loginModel = new LoginModel([
            'scenario' => LoginModel::SCE_VERIFY_IDENTITY,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($result = $loginModel->process()){
            return $this->success($result);
        }else{
            return $this->failure($loginModel->getErrorCode());
        }
    }
}
