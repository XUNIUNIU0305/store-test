<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/6/7
 * Time: 上午9:42
 */

namespace mobile\modules\member\controllers;


use mobile\modules\member\models\RegisterModel;

class RegisterController extends Controller
{
    protected $access = [
        'index' => ['?', 'get'],
        'register' => ['?', 'post'],
        'checkaccountstatus'=>['?','post'],
    ];

    protected $actionUsingDefaultProcess = [
        'register' => RegisterModel::SCE_SIGN_UP,
        'checkaccountstatus'=>RegisterModel::SCE_CHECK_ACCOUNT,
        '_model' => 'mobile\modules\member\models\RegisterModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}