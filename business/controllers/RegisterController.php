<?php
namespace business\controllers;

use Yii;
use business\models\RegisterModel;

class RegisterController extends Controller{

    protected $access = [
        'validate' => ['?', 'post'],
        'register' => ['?', 'post'],
        'index' => ['?', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'validate' => RegisterModel::SCE_VALIDATE_REGISTERCODE,
        'register' => RegisterModel::SCE_REGISTER_ACCOUNT,
        '_model' => 'business\models\RegisterModel',
    ];

    public $layout = 'index';

    public function actionIndex(){
        return $this->render('index');
    }
}
