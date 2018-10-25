<?php
namespace custom\controllers;

use Yii;
use common\controllers\Controller;
use custom\models\RegisterModel;

class RegisterController extends Controller{

    protected $access = [
        'index' => ['?', 'get'],
        'register' => ['?', 'post'],
        'checkaccountstatus'=>['?','post'],
    ];

    protected $actionUsingDefaultProcess = [
        'register' => RegisterModel::SCE_SIGN_UP,
        'checkaccountstatus'=>RegisterModel::SCE_CHECK_ACCOUNT,
        '_model' => '\custom\models\RegisterModel',
    ];

    public $layout = 'global';

    public function actionIndex(){
        return $this->render('index');
    }

}
