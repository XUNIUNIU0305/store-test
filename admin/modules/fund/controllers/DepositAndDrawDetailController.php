<?php
namespace admin\modules\fund\controllers;

use Yii;
use admin\controllers\Controller;
use admin\modules\fund\models\DepositAndDrawDetailModel;

class DepositAndDrawDetailController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'detail' => ['@', 'get'],
        'operate-info' => ['@', 'get'],
        'pass' => ['@', 'post'],
        'cancel' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'detail' => [
            'scenario' => DepositAndDrawDetailModel::SCE_GET_DETAIL,
            'convert' => false,
        ],
        'operate-info' => [
            'scenario' => DepositAndDrawDetailModel::SCE_GET_OPERATE_INFO,
            'convert' => false,
        ],
        'pass' => DepositAndDrawDetailModel::SCE_PASS,
        'cancel' => DepositAndDrawDetailModel::SCE_CANCEL,
        '_model' => DepositAndDrawDetailModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
