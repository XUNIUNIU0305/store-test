<?php
namespace supply\controllers;

use Yii;
use common\controllers\Controller;
use supply\models\IndexModel;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class IndexController extends Controller{

    public $layout = 'index';

    protected $access = [
        'index' => [null, 'get'],
        'login' => ['?', 'post'],
        'logout' => ['@', 'get'],
        'verify-captcha' => [null, 'post'],
        'captcha' => [null, 'get'],
        'error' => [null, 'get'],
        'api-hostname' => [null, 'get'],
    ];

    /**
     * 展示首页
     * 如果已登陆，则跳转至main
     */
    public function actionIndex(){
        if(Yii::$app->user->isGuest){
            return $this->render('index');
        }else{
            $this->redirect(Url::toRoute('/main'));
        }
    }

    /**
     * 验证【验证码】
     */
    public function actionVerifyCaptcha(){
        return $this->success(['result' => IndexModel::verifyCaptcha(Yii::$app->request->post('captcha', ''))]);
    }

    /**
     * 执行登陆
     */
    public function actionLogin(){
        $indexModel = new IndexModel();
        $indexModel->scenario = IndexModel::SCE_LOGIN;
        $indexModel->attributes = Yii::$app->request->post();
        if($indexModel->verifyUser()){
            Yii::$app->user->login($indexModel->getUserIdentity());
            return $this->success(['url' => Url::to('main')]);
        }else{
            return $this->failure($indexModel->getErrorCode());
        }
    }

    /**
     * 执行登出
     */
    public function actionLogout(){
        Yii::$app->user->logout();
        $this->redirect(Yii::$app->user->loginUrl);
    }

    public function actionApiHostname(){
        return $this->success(['hostname' => Yii::$app->params['API_Hostname']]);
    }

    /**
     * 静态action
     * 获取验证码，错误提示页面
     */
    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'transparent' => true,
                'width' => 70,
                'height' => 40,
                'padding' => 0,
                'offset' => 0,
                'minLength' => 4,
                'maxLength' => 4,
                'foreColor' => 0xcccccc,
                'backColor' => 0x5f5f5f,
                'fontFile' => '@common/assets/font/edited.ttf',
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

//    public function actionError()
//    {
//        if(!$exception = Yii::$app->errorHandler->exception){
//            $exception = new NotFoundHttpException('404');
//        }
//        var_dump($exception->getMessage());
//    }
}
