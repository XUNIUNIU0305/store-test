<?php
namespace custom\modules\account\controllers;

use Yii;
use common\controllers\Controller;
use custom\modules\account\models\IndexModel;

class IndexController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'menu' => ['@', 'get'],
        'recharge-method' => ['@', 'get'],
        'recharge' => ['@', 'post'],
        'balance' => ['@', 'get'],
        'express' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'express' => IndexModel::SCE_GET_EXPRESS,
        'recharge' => IndexModel::SCE_RECHARGE,
        '_model' => '\custom\modules\account\models\IndexModel',
    ];

    public function actionBalance(){
        return $this->success(IndexModel::getUserBalance());
    }

    public function actionRechargeMethod(){
        return $this->success(IndexModel::getRechargeMethod());
    }

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionMenu(){
        return $this->success(IndexModel::getMenu());
    }
}
